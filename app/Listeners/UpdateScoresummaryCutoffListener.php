<?php

namespace App\Listeners;

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

        $summaries = Scoresummary::where('eventversion_id', $event->eventversion_id)
            ->where('instrumentation_id', $event->instrumentation_id)
            ->where('score_count', $totalscorecount)
            ->get();

        if($eventversion->eventversionconfig->bestscore === 'asc'){ //scores at and lower than cutoff are accepted

            foreach($summaries AS $summary) {

                if($summary->score_count < $totalscorecount){

                    $summary->update(['result' => 'inc']);

                }else {

                    $summary->update(['result' => ($summary->score_total > $event->cutoff) ? 'n/a' : 'acc']);
                }
            }

        }else{ //scores at and greater than cutoff are accepted

            foreach($summaries AS $summary) {

                $summary->update(['result' => ($summary->score_total >= $event->cutoff) ? 'acc' : 'n/a']);
            }

        }

    }
}
