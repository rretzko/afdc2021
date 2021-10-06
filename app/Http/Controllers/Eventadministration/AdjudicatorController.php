<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Adjudicator;
use App\Models\Eventversion;
use App\Models\Membership;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class AdjudicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.adjudicators.index',
            [
                'member' => new Membership(),
                'members' => $eventversion->event->organization->memberships->sortBy(['user.person.last','user.person.first']),
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
        //
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

        Adjudicator::updateOrCreate(
            [
                'eventversion_id' => Eventversion::find(Userconfig::getValue('eventversion', auth()->id()))->id,
                'room_id' => $inputs['room_id'],
                'user_id' => $inputs['user_id'],
            ],
            [
                'adjudicatorstatustype_id' => 1,
            ]
        );

        return $this->index();

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
