<?php

namespace App\Listeners;

use App\Events\UpdateScoresummaryCutoffEvent;
use App\Models\Eventensemblecutoff;
use App\Models\Eventensemblecutofflock;
use App\Models\Eventversion;
use App\Models\Scoresummary;
use App\Models\Scoringcomponent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateScoresummaryCutoffListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * $event->eventversion_id
     * $event->eventensemble_id
     * $event->instrumentation_id
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $eventversion = Eventversion::find($event->eventversion_id);
        $scoringcomponentcount = Scoringcomponent::where('eventversion_id', $eventversion->id)->count();
        $adjudicatorcount = $eventversion->eventversionconfig->judge_count;
        $totalscorecount = ($scoringcomponentcount * $adjudicatorcount);

        $details = $this->ensemblesDetails($eventversion, $event);

        $ensembles = $eventversion->eventensembles;

        $summaries = Scoresummary::where('eventversion_id', $event->eventversion_id)
            ->where('instrumentation_id', $event->instrumentation_id)
            ->where('score_count', $totalscorecount)
            ->get();

        if($eventversion->eventversionconfig->bestscore === 'asc'){ //scores at and lower than cutoff are accepted

            foreach($summaries AS $summary) {

                if($summary->score_count < $totalscorecount){

                    $summary->update(['result' => 'inc']);

                }else {

                    if($ensembles->count() === 1) { //event has one ensemble

                        $summary->update(['result' => ($summary->score_total > $event->cutoff) ? 'n/a' : 'acc']);

                    }else{ //event has multiple ensembles

                        $this->multipleEnsembleCutoffs($eventversion, $summary, $event, $details);
                    }
                }
            }

        }else{ //scores at and greater than cutoff are accepted

            foreach($summaries AS $summary) {

                $summary->update(['result' => ($summary->score_total >= $event->cutoff) ? 'acc' : 'n/a']);
            }

        }

    }

    private function ensemblesDetails(Eventversion $eventversion,UpdateScoresummaryCutoffEvent $event): array
    {
        //assemble scores in descending score order
        $details = [];
        foreach($eventversion->event->eventensembles AS $ensemble) {

            $cutoff = Eventensemblecutoff::where('eventversion_id', $eventversion->id)
                    ->where('eventensemble_id', $ensemble->id)
                    ->first() ?? false;

            $lock = Eventensemblecutofflock::where('eventversion_id', $eventversion->id)
                ->where('eventensemble_id', $ensemble->id)
                ->first() ?? false;

            $details[] =
                [
                    'cutoff' => $cutoff->cutoff,
                    'locked' => $lock ? $lock->locked : false,
                    'id' => $ensemble->id,
                    'abbr' => $ensemble->acceptance_abbr,
                ];
        }

        rsort($details);

        return $details;
    }

    private function multipleEnsembleCutoffs(Eventversion $eventversion,Scoresummary $summary, UpdateScoresummaryCutoffEvent $event, array $details)
    {
        $result = 'n/a';

        foreach($details AS $key => $ensemble){

            if($ensemble['cutoff']){ //else do nothing

                if($summary->score_total <= $ensemble['cutoff'] ) {

                    $result = $ensemble['abbr'];
                }
            }
        }

        $summary->update(['result' => $result]);
    }
}
