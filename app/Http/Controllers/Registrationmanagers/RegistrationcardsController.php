<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Instrumentation;
use App\Models\Room;
use App\Models\Utility\RegistrationActivity;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class RegistrationcardsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @props \App\Models\Eventversion $eventversion
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $bladepath = 'x-registrationcards.'.$eventversion->event->id.'.'.$eventversion->id.'.registrationcard';
        return view('registrationmanagers.registrationcards.index',[
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
        $bladepath = 'x-registrationcards.'.$eventversion->event->id.'.'.$eventversion->id.'.registrationcard';
        $eventversionrooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $rooms = $eventversionrooms->filter(function($room) use($instrumentation){
           return $room->instrumentations->contains($instrumentation);
        })
            ->values(); //reset collection keys

        return view('registrationmanagers.registrationcards.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'targetinstrumentation' => $instrumentation,
            'instrumentations' => $eventversion->instrumentations(),
            'registrationactivity' => new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]),
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
        $registrants = $ra->registrantsBySchoolNameFullnameAlpha($instrumentation);

        $eventversionrooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $rooms = $eventversionrooms->filter(function($room) use($instrumentation){
            return $room->instrumentations->contains($instrumentation);
        })
        ->values();

        //upper voicing instrumentations
        /** @todo should be a configuration option */
        $doubleformats = [63,64,65,66,67,68,69,70];

        $view = 'pdfs.registrationcards.';
        $view .= $eventversion->event->id.'.';
        $view .= $eventversion->id.'.';
        $view .= in_array($instrumentation->id, $doubleformats) ? 'double' : 'single';

        $pdf = PDF::loadView($view,
        compact('eventversion','instrumentation','registrants','rooms'))
        ->setPaper('letter','portrait');

        return $pdf->download('registrationcards.pdf');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
