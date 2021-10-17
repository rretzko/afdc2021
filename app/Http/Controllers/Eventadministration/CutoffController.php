<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventensemblecutoff;
use App\Models\Eventensemblecutofflock;
use App\Models\Eventversion;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class CutoffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $adjudication = new \App\Models\Utility\Adjudication(['eventversion' => $eventversion]);

        $eventensemblecutoff = new Eventensemblecutoff;
        $eventensemblecutoff->eventversion = $eventversion;

        $eventensemblelocks = collect();
        foreach($eventversion->event->eventensembles AS $eventensemble){

            $eventensemblelocks->push(Eventensemblecutofflock::where('eventversion_id', $eventversion->id)
                ->where('eventensemble_id', $eventensemble->id)
                ->first());
        }

        return view('eventadministration.cutoffs.index',
        [
            'adjudication' => $adjudication,
            'eventensembles' => $eventversion->event->eventensembles,
            'eventensemblecutoff' => $eventensemblecutoff,
            'eventensemblelocks' => $eventensemblelocks,
            'eventversion' => Eventversion::find(Userconfig::getValue('eventversion', auth()->id())),
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
     * @param  int  $score
     * @return \Illuminate\Http\Response
     */
    public function update($instrumentation_id, $cutoff)
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $eventensembles = $eventversion->event->eventensembles;

        foreach($eventensembles AS $eventensemble){

            $eventensemblecutofflock = Eventensemblecutofflock::where('eventversion_id', $eventversion->id)
                ->where('eventensemble_id', $eventensemble->id)
                ->first() ?? new Eventensemblecutofflock;

            if(! $eventensemblecutofflock->locked()){

                //create or update the cutoff data
                $eventensemblecutoff = new \App\Models\Eventensemblecutoff;

                $eventensemblecutoff::updateOrCreate(
                    [
                        'eventversion_id' => $eventversion->id,
                        'eventensemble_id' => $eventensemble->id,
                        'instrumentation_id' => $instrumentation_id,
                    ],
                    [
                        'cutoff' => $cutoff,
                        'user_id' => auth()->id(),
                    ],
                );

                break;
            }
        }

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