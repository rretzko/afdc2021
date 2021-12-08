<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventensemblecutofflock;
use App\Models\Eventversion;
use App\Models\Eventensemble;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class CutofflockController extends Controller
{
    public function update(Eventversion $eventversion, Eventensemble $eventensemble)
    {
        if($eventversion->eventversionconfig->alternating_scores){

            return $this->updateAlternatingScores($eventversion);
        }

        $current = \App\Models\Eventensemblecutofflock::where('eventversion_id', $eventversion->id)
            ->where('eventensemble_id', $eventensemble->id)
            ->first() ?? new \App\Models\Eventensemblecutofflock;

        \App\Models\Eventensemblecutofflock::updateOrCreate(
            [
                'eventversion_id' => $eventversion->id,
                'eventensemble_id' => $eventensemble->id,
            ],
            [
                'locked' => ($current->locked()) ? 0 : 1,
                'user_id' => auth()->id(),
            ]
        );

        return redirect(route('eventadministrator.tabroom.cutoffs',[
            'eventversion' => $eventversion,
        ]));
    }

    /**
     * Lock or Unlock ALL ensemble cutoff locks
     * @param Eventversion $eventversion
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function updateAlternatingScores(Eventversion $eventversion)
    {
        $eventensembles = $eventversion->event->eventensembles;

        $current = Eventensemblecutofflock::where('eventversion_id', $eventversion->id)
                ->first() ?? new \App\Models\Eventensemblecutofflock;

        $currentlock = ($current->id) ? $current->locked : 0;

        foreach($eventensembles AS $eventensemble){

            Eventensemblecutofflock::updateOrCreate(
                [
                    'eventversion_id' => $eventversion->id,
                    'eventensemble_id' => $eventensemble->id,
                ],
                [
                    'locked' => ($currentlock) ? 0 : 1,
                    'user_id' => auth()->id(),
                ]
            );
        }

        return redirect(route('eventadministrator.tabroom.cutoffs',[
            'eventversion' => $eventversion,
        ]));
    }
}
