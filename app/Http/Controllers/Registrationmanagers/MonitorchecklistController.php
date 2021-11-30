<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Instrumentation;
use App\Models\Room;
use App\Models\Utility\RegistrationActivity;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class MonitorchecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $bladepath = 'x-monitorchecklists.'.$eventversion->event->id.'.'.$eventversion->id.'.monitorchecklist';
        return view('registrationmanagers.monitorchecklists.index',[
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
     * @param  \App\Models\Instrumentation $instrumentations
     * @return \Illuminate\Http\Response
     */
    public function show(Eventversion $eventversion, Instrumentation $instrumentation)
    {
        $bladepath = 'x-registrationcards.'.$eventversion->event->id.'.'.$eventversion->id.'.registrationcard';
        $eventversionrooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $rooms = $eventversionrooms->filter(function($room) use($instrumentation){
            return $room->instrumentations->contains($instrumentation);
        })
            ->values(); //reset collection keys

        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        return view('registrationmanagers.monitorchecklists.index',[
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
        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);
        $registrants = $registrationactivity->registrantsByTimeslotSchoolNameFullnameAlpha($instrumentation);

        $eventversionrooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $rooms = $eventversionrooms->filter(function($room) use($instrumentation){
            return $room->instrumentations->contains($instrumentation);
        })
            ->values();

        $view = 'pdfs.monitorchecklists.';
        //$view .= $eventversion->event->id.'.';
        //$view .= $eventversion->id.'.';
        $view .= 'monitorchecklist';

        $pdf = PDF::loadView($view,
            compact('eventversion','instrumentation','registrants','rooms'))
            ->setPaper('letter','portrait');

        return $pdf->download('monitorchecklists_'.str_replace(' ','-',$instrumentation->formattedDescr()).'.pdf');
    }
}
