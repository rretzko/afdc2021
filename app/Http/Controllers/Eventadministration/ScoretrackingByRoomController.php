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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.scoretrackingbyrooms.show',
            [
                'eventversion' => $eventversion,
                'rooms' => $eventversion->rooms->sortBy('order_by'),
                'room' => $room,

            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
