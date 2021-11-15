<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Adjudicator;
use App\Models\Eventversion;
use App\Models\Membership;
use App\Models\Room;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class AdjudicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Models\Eventversion $eventversion)
    {//dd($eventversion->event->organization->memberships->sortBy(['user.person.last','user.person.first']));
        return view('eventadministration.adjudicators.index',
            [
                'eventversion' => $eventversion,
                'member' => new Membership(),
                'members' => $eventversion->event->organization->memberships->sortBy(['user.person.last','user.person.first']),
                'rooms' => $eventversion->rooms->sortBy('order_by'),
                'adjudicator' => NULL,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $eventversion = Eventversion::find(Adjudicator::find($id)->eventversion_id);

        return view('eventadministration.adjudicators.index',
            [
                'eventversion' => $eventversion,
                'member' => new Membership(),
                'members' => $eventversion->event->organization->memberships->sortBy(['user.person.last','user.person.first']),
                'rooms' => $eventversion->rooms->sortBy('order_by'),
                'adjudicator' => Adjudicator::find($id),
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $inputs = $request->validate([
           'room_id' => ['required', 'numeric'],
           'user_id' => ['required', 'numeric'],
        ]);

        $eventversion = Eventversion::find(Room::find($inputs['room_id'])->eventversion_id);

        Adjudicator::updateOrCreate(
            [
                'eventversion_id' => $eventversion->id,
                'room_id' => $inputs['room_id'],
                'user_id' => $inputs['user_id'],
            ],
            [
                'adjudicatorstatustype_id' => 1,
            ]
        );

        return $this->index($eventversion);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $eventversion = Eventversion::find(Adjudicator::find($id)->eventversion_id);

        Adjudicator::destroy($id);

        return $this->index($eventversion);
    }
}
