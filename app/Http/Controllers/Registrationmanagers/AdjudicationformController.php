<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Instrumentation;
use App\Models\Room;
use App\Models\Utility\RegistrationActivity;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class AdjudicationformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        if($eventversion->id == 72){ //NJ All-Shore

            return redirect()->route('registrationmanagers.adjudicationformsbyroom.show',
            [
                'eventversion' => $eventversion,
                'room' => $eventversion->rooms->first(),
            ]);
        }

        $bladepath = 'x-adjudicationforms.'.$eventversion->event->id.'.'.$eventversion->id.'.adjudicationform';
        return view('registrationmanagers.adjudicationforms.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'targetinstrumentation' => NULL,
            'instrumentations' => $eventversion->instrumentations(),
            'registrationactivity' => new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eventversion $eventversion
     * @param  \App\Models\Instrumentation $instrumentation
     * @return \Illuminate\Http\Response
     */
    public function show(Eventversion $eventversion, Instrumentation $instrumentation)
    {
        $bladepath = 'x-adjudicationforms.'.$eventversion->event->id.'.'.$eventversion->id.'.adjudicationform';

        $eventversionrooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $rooms = $eventversionrooms->filter(function($room) use($instrumentation){
            return $room->instrumentations->contains($instrumentation);
        })
            ->values(); //reset collection keys

        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        return view('registrationmanagers.adjudicationforms.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'targetinstrumentation' => $instrumentation,
            'instrumentations' => $eventversion->instrumentations(),
            'registrants' => $registrationactivity->registrantsByTimeslotSchoolNameFullnameAlpha($instrumentation),
            'registrationactivity' => $registrationactivity,
            'rooms' => $rooms,
        ]);
    }

    /**
     * download a pdf
     *
     * @param  \App\Models\Eventversion $eventversion
     * @param  \App\Models\Instrumentation $instrumentation
     */
    public function pdf(Eventversion $eventversion, Instrumentation $instrumentation)
    {
        $ra = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);
        $registrants = $ra->registrantsByTimeslotSchoolNameFullnameAlpha($instrumentation);

        $eventversionrooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $rooms = $eventversionrooms->filter(function($room) use($instrumentation){
            return $room->instrumentations->contains($instrumentation);
        })
            ->values();

        $view = 'pdfs.adjudicationforms.';
        $view .= $eventversion->event->id.'.';
        $view .= $eventversion->id.'.';
        $view .= 'adjudicationform';

        $pdf = PDF::loadView($view,
            compact('eventversion','instrumentation','registrants','rooms'))
            ->setPaper('letter','portrait');

        return $pdf->download('adjudicationForm_'.str_replace(' ','_',$instrumentation->formattedDescr()).'.pdf');
    }
}
