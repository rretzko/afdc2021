<?php

namespace App\Listeners;

use App\Models\Eventversion;
use App\Models\Scoresummary;
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

        $summaries = Scoresummary::where('eventversion_id', $event->eventversion_id)
            ->where('instrumentation_id', $event->instrumentation_id)
            ->get();

        if($eventversion->eventversionconfig->bestscore === 'asc'){ //scores at and lower than cutoff are accepted

            foreach($summaries AS $summary) {

                $summary->update(['result' => ($summary->score_total > $event->cutoff) ? 'n/a' : 'acc']);
            }

        }else{ //scores at and greater than cutoff are accepted

            foreach($summaries AS $summary) {

                $summary->update(['result' => ($summary->score_total >= $event->cutoff) ? 'acc' : 'n/a']);
            }

        }

    }
}
