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
            'targetroom' => new Room(),
            'instrumentations' => $eventversion->instrumentations(),
            'registrationactivity' => new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]),
            'rooms' => $eventversion->rooms,
        ]);
    }

   /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eventversion $eventversion
     * @param  \App\Models\Room $room
     * @return \Illuminate\Http\Response
     */
    public function show(Eventversion $eventversion, Room $room)
    {
        $bladepath = 'x-registrationcards.'.$eventversion->event->id.'.'.$eventversion->id.'.registrationcard';
        //$eventversionrooms = Room::with('instrumentations')
        //    ->where('eventversion_id', $eventversion->id)
        //    ->get();

        //$rooms = $eventversionrooms->filter(function($room) use($instrumentation){
        //    return $room->instrumentations->contains($instrumentation);
       // })
         //   ->values(); //reset collection keys

        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        return view('registrationmanagers.monitorchecklists.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'targetroom' => $room,
            'rooms' => $eventversion->rooms,
            //'registrants' => $registrationactivity->registrantsByTimeslotSchoolNameFullnameAlpha($instrumentation),
            'registrants' => $registrationactivity->registrantsByRoomById($room),
            'registrationactivity' => $registrationactivity,
        ]);
    }

    /**
     * download a pdf
     *
     * @param  \App\Models\Eventversion $eventversion
     * @param  \App\Models\Instrumentation $instrumentation
     */
    public function pdf(Eventversion $eventversion, Room $room)
    {
        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);
        $registrants = $registrationactivity->registrantsByRoomById($room);

        //$eventversionrooms = Room::with('instrumentations')
        //    ->where('eventversion_id', $eventversion->id)
        //    ->get();

        //$rooms = $eventversionrooms->filter(function($room) use($instrumentation){
        //    return $room->instrumentations->contains($instrumentation);
        //})
        //    ->values();

        $rooms = $eventversion->rooms;

        $view = 'pdfs.monitorchecklists.';
        //$view .= $eventversion->event->id.'.';
        //$view .= $eventversion->id.'.';
        $view .= 'monitorchecklist';

        $pdf = PDF::loadView($view,
            compact('eventversion','room','registrants','rooms'))
            ->setPaper('letter','portrait');

        return $pdf->download('monitorchecklists_'.str_replace(' ','-',$room->descr).'.pdf');
    }
}
