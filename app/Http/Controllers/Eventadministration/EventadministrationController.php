<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewEventRequest;
use App\Models\Eventversionrole;
use App\Models\Organization;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class EventadministrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Models\Event $event)
    {
        Userconfig::updateValue('event', auth()->id(), $event->id);

        $eventversionroles = Eventversionrole::where('user_id', auth()->id())
            ->get();

        $eventversions = $event->eventversions->filter(function($eventversion) use($event,$eventversionroles){

            foreach($eventversionroles AS $role){

                if(($eventversion->event->id === $event->id) && ($eventversion->id === $role->eventversion_id)){

                    return true;
                }
            }
        })->sortByDesc('senior_class_of');

        return view('eventadministration.eventversions.index',
        [
            'event' => $event,
            'eventversions' => $eventversions,
            'eventversionroles' => $eventversionroles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('eventadministration.create',
        [
            'organizations' => Organization::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NewEventRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewEventRequest $request)
    {
        dd(__METHOD__);
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
