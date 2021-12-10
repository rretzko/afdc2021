<?php

namespace App\Listeners;

use App\Events\UpdateScoresummaryAlternatingCutoffEvent;
use App\Models\Eventensemblecutoff;
use App\Models\Scoresummary;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\InteractsWithQueue;

class UpdateScoresummaryAlternatingCutoffListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(UpdateScoresummaryAlternatingCutoffEvent $event)
    {
        $a = [];
        //find cutoff for first ensembles (i.e. should be the same cut-off number
        $eventensemblecutoff = Eventensemblecutoff::where('eventversion_id', $event->eventversion->id)
            ->where('eventensemble_id', $event->eventensembles->first()->id)
            ->where('instrumentation_id', $event->instrumentation_id)
            ->first();

        $cutoff = ($eventensemblecutoff) ? $eventensemblecutoff->cutoff : 0;

        //collect all scoresummaries for $instrumentation_id
        $scoresummaries = Scoresummary::where('eventversion_id', $event->eventversion->id)
            ->where('instrumentation_id', $event->instrumentation_id)
          //  ->where('score_total', ($event->eventversion->eventversionconfig->bestscore === 'asc') ? '<=' : '>=', $cutoff)
            ->orderBy('score_total')
            ->get();

        $uniquescores = $this->uniqueScores($scoresummaries);

        $countscoringcomponents = ($event->eventversion->scoringcomponents()->count() * $event->eventversion->eventversionconfig->judge_count);

        //if instrumentation_id is contained in both ensembles
        if($event->eventensembles[1]->instrumentations()->contains($event->instrumentation_id)) {

            //separate scores per eventensemble for $instrumentation_id by alternating rows
            foreach ($event->eventensembles as $key => $eventensemble) {//identify eventensemble by $key

                foreach ($uniquescores as $loop => $score) {//loop through scoresummaries

                    if (($loop % 2) === $key) {//link scoresummary to [0] or [1] eventensemble

                        $a[$key][] = $score;
                    }
                }
            }
        }else{ //only the first ensemble has $instrumentation_id

            foreach($uniquescores AS $score){

                $a[0][] = $score;
            }
        }

        //update scoresummaries->results with abbreviation
        foreach($scoresummaries AS $scoresummary){

            $scorecount = $scoresummary->score_count;
            $result = NULL;

            foreach($event->eventensembles AS $key => $eventensemble) {

                if($eventensemble->instrumentations()->contains($event->instrumentation_id)) {

                    if (!$scorecount) {

                        $result = 'pend';//ing

                    } elseif ($scorecount < $countscoringcomponents) {

                        $result = 'inc';//omplete

                    } elseif ($scorecount > $countscoringcomponents) {

                        $result = 'err';//or

                    } elseif ($event->eventversion->eventversionconfig->bestscore === 'asc' ? $scoresummary->score_total > $cutoff : $scoresummary->score_total < $cutoff) {

                        $result = 'n/a';

                    } elseif (in_array($scoresummary->score_total, $a[$key])) {

                        $result = $eventensemble->acceptance_abbr;
                    }

                    if ($result) {
                        $scoresummary->update([
                            'result' => $result, $eventensemble->acceptance_abbr,
                        ]);
                    }
                }
            }
        }
    }

    private function uniqueScores(Collection $scoresummaries) : array
    {
        $a = [];

        foreach($scoresummaries AS $scoresummary){

            if(! in_array($scoresummary->score_total, $a)){
                $a[] = $scoresummary->score_total;
            }
        }

        return $a;
    }
}
