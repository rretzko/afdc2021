<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Instrumentation;
use App\Models\Room;
use App\Models\Utility\RegistrationActivity;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class AdjudicationformByRoomController extends Controller
{
    /**
     * Display the eventversion rooms
     *
     * @param  \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $bladepath = 'x-adjudicationforms.'.$eventversion->event->id.'.'.$eventversion->id.'.adjudicationform';

        $rooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        return view('registrationmanagers.adjudicationforms.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'room' => new Room(),
            'registrants' => null,
            'registrationactivity' => $registrationactivity,
            'rooms' => $rooms,
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
        $bladepath = 'x-adjudicationforms.'.$eventversion->event->id.'.'.$eventversion->id.'.adjudicationform';

        $rooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        return view('registrationmanagers.adjudicationforms.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'room' => $room,
            'registrants' => $registrationactivity->registrantsByRoomById($room),
            'registrationactivity' => $registrationactivity,
            'rooms' => $rooms,
        ]);
    }

    /**
     * download a pdf
     *
     * @param  \App\Models\Eventversion $eventversion
     * @param  \App\Models\Room $room
     */
    public function pdf(Eventversion $eventversion, Room $room)
    {
        $ra = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        $eventversionrooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $rooms = Room::with('instrumentations')
            ->where('eventversion_id', $eventversion->id)
            ->get();

        $registrants = $ra->registrantsByRoomById($room);

        $view = 'pdfs.adjudicationforms.';
        $view .= $eventversion->event->id.'.';
        $view .= $eventversion->id.'.';
        $view .= 'adjudicationform';

        $pdf = PDF::loadView($view,
            compact('eventversion','registrants','room','rooms'))
            ->setPaper('letter','portrait');

        return $pdf->download('adjudicationForm_'.str_replace(' ','_',$room->descr).'.pdf');
    }
}
