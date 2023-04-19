<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Eventversion;
use App\Models\Room;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
use Illuminate\Http\Request;

class ScoretrackingByRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        //$registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

         return view('eventadministration.scoretrackingbyrooms.index',
        [
           'eventversion' => $eventversion,
           'rooms' => $eventversion->rooms->sortBy('order_by'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        $auditionees = $room->auditionees();
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.scoretrackingbyrooms.show',
            [
                'eventversion' => $eventversion,
                'rooms' => $eventversion->rooms->sortBy('order_by'),
                'room' => $room,
                'auditionees' => $auditionees,
                'auditioneeCount' => $auditionees->count(),

            ]);
    }

}
