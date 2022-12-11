<?php

namespace App\Http\Controllers\Eventadministration;

use App\Events\UpdateScoresummaryAlternatingCutoffEvent;
use App\Events\UpdateScoresummaryCutoffEvent;
use App\Http\Controllers\Controller;
use App\Models\Eventensemble;
use App\Models\Eventensemblecutoff;
use App\Models\Eventensemblecutofflock;
use App\Models\Eventversion;
use App\Models\Instrumentation;
use App\Models\Scoresummary;
use App\Models\Userconfig;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CutoffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $adjudication = new \App\Models\Utility\Adjudication(['eventversion' => $eventversion]);

        $eventensemblecutoff = new Eventensemblecutoff;
        $eventensemblecutoff->eventversion = $eventversion;

        //assemble a collection of cutoff locks per eventensemble
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
            'eventversion' => $eventversion,
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
     * @param  int  $eventversion_id
     * @param  int  $instrumentation_id,
     * @param  int  $cutoff
     * @return \Illuminate\Http\Response
     */
    public function update($eventversion_id, $instrumentation_id, $cutoff)
    {
        $eventversion = Eventversion::find($eventversion_id);
        $eventensembles = $eventversion->event->eventensembles;

        //default eventensemble acceptance algorithm is serial acceptance of scores above/below $cutoff based on $eventversion->eventversionconfig->bestscore
        //alternate algorithm is based on alternating scores triggered by $eventversion->eventversionconfig->alternating_scores property
        if($eventversion->eventversionconfig->alternating_scores){

            return $this->updateAlternatingScores($eventversion, $eventensembles, $instrumentation_id, $cutoff);

        }else{

            return $this->updateSerialScores($eventversion, $eventensembles, $instrumentation_id, $cutoff);
        }
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

    private function locked(Eventensemble $eventensemble, Eventversion $eventversion) : bool
    {
        $lock = Eventensemblecutofflock::where('eventensemble_id', $eventensemble->id)
                ->where('eventversion_id', $eventversion->id)
                ->first() ?? new Eventensemblecutofflock;

        return ( $lock->id && $lock->locked);
    }

    /**
     * alternate eventensemble acceptance
     *
     * @param Eventversion $eventversion
     * @param Collection $eventensembles
     * @param int $instrumentation_id
     * @param int $cutoff
     */
    private function updateAlternatingScores(Eventversion $eventversion, Collection $eventensembles, int $instrumentation_id, int $cutoff)
    {
        //update eventensemblecutoffs table
        $this->updateEventensemblecutoffsTable($eventversion, $eventensembles, $instrumentation_id, $cutoff);

        if(! $this->locked($eventensembles[0], $eventversion)) {

            event(new UpdateScoresummaryAlternatingCutoffEvent($eventversion, $eventensembles, $instrumentation_id));
        }

        return $this->index($eventversion);
    }

    private function updateEventensemblecutoffsTable(Eventversion $eventversion, Collection $eventensembles, int $instrumentation_id, int $cutoff)
    {
        foreach($eventensembles AS $eventensemble){

            if(($eventensemble->instrumentations()->contains($instrumentation_id))) {

                if(! $this->locked($eventensemble, $eventversion)) {

                    Eventensemblecutoff::updateOrCreate(
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
                }else{

                }
            }
        }
    }

    /**
     * default eventensemble acceptance
     *
     * @param Eventversion $eventversion
     * @param Collection $eventensembles
     * @param int $instrumentation_id
     * @param int $cutoff
     * @return \Illuminate\Http\Response
     */
    private function updateSerialScores(Eventversion $eventversion, Collection $eventensembles, int $instrumentation_id, int $cutoff)
    {
        $this->updateEventensemblecutoffsTable($eventversion, $eventensembles, $instrumentation_id, $cutoff);

        if(! $this->eventEnsemblesAreLocked($eventversion, $eventensembles)) {

            event(new UpdateScoresummaryCutoffEvent($eventversion->id, $instrumentation_id, $cutoff));
        }

        return $this->index($eventversion);
    }

    /**
     * Return boolean true if all eventensembles are locked
     */
    private function eventEnsemblesAreLocked(Eventversion $eventversion, Collection $eventensembles): bool
    {
        $locked = true;

        foreach($eventensembles AS $eventensemble){

            if((! Eventensemblecutofflock::where('eventversion_id', $eventversion->id)) ||
                (! Eventensemblecutofflock::where('eventversion_id', $eventversion->id)->where('eventensemble_id', $eventensemble->id)->first()) ||
                (! Eventensemblecutofflock::where('eventversion_id', $eventversion->id)->where('eventensemble_id', $eventensemble->id)->first()->locked)){

                return false;
            }
        }

        return $locked;
    }
}
