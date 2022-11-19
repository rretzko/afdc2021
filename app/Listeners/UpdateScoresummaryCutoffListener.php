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
                    'cutoff' => $cutoff ? $cutoff->cutoff : $event->cutoff, //85
                    'locked' => $lock ? $lock->locked : false,
                    'id' => $ensemble->id,
                    'abbr' => $ensemble->acceptance_abbr,
                    'instrumentations' => $ensemble->instrumentations(),
                ];
            //if($ensemble->id == 25){dd($details);}
        }

        rsort($details);

        return $details;
    }

    /**
     * @param Eventversion $eventversion
     * @param Scoresummary $summary
     * @param UpdateScoresummaryCutoffEvent $event
     * @param array $details //ensemble cut-off details [cutoff, locked, id, abbr, instrumentations]
     * @return void
     */
    private function multipleEnsembleCutoffs(Eventversion $eventversion,Scoresummary $summary, UpdateScoresummaryCutoffEvent $event, array $details)
    {
        if($eventversion->id == 73){
            $this->mahcWorkaround($eventversion, $summary, $event, $details);
            return true;
        }
        //dd($details);
        $result = 'n/a';
//foreach($details AS $ensemble){
 //   echo $ensemble['cutoff'].'<br />';
//}
//dd(__LINE__);
        foreach($details AS $key => $ensemble){
//dd($ensemble);
            if($ensemble['cutoff']){ //else do nothing
//if($ensemble['instrumentations']->contains($summary->instrumentation_id)){ dd($summary->score_total.': '.$ensemble['cutoff']);}
                if(($ensemble['instrumentations']->contains($summary->instrumentation_id)) && ($summary->score_total <= $ensemble['cutoff'] ) ){

                    $result = $ensemble['abbr'];
                }
            }
        }

        $summary->update(['result' => $result]);
    }

    public function mahcWorkaround(Eventversion $eventversion, Scoresummary $summary, $event, array $details)
    {
        /*
         * Scoresummary:
         *  "id" => 2199, "eventversion_id" => 73, "registrant_id" => 731273, "instrumentation_id" => 5
         *  "score_total" => 102, "score_count" => 21, "result" => "n/a"
         */

        /*
         * Event:
         *   +cutoff: 99, eventversion_id: 73, instrumentation_id: 5,
         */

        /*
         * Details: array
         * [0] = ["cutoff" => 98.0, "locked" => 0, "id" => 24, "abbr" => "ms", "instrumentations"]
         * [1] =  ["cutoff" => 60.0, "locked" => 0, "id" => 25, "abbr" => "hs", "instrumentations"]
         */
        $cutoff = $event->cutoff;
        $ensemble_ids = [1 => 24, 5 => 24, 72 => 24, 73 => 24, //middle school
            63 => 25, 64 => 25, 65 => 25, 66 => 25, 67 => 25, 68 => 25, 69 => 25, 70 => 25]; //high school
        $eventversion_id = $event->eventversion_id;
        $instrumentation_id = $event->instrumentation_id;
        $ensemble_id = $ensemble_ids[$instrumentation_id];
        $results = [24 => 'ms', 25 => 'hs'];
        $scoresummaries = Scoresummary::where('eventversion_id', $eventversion_id)
            ->where('instrumentation_id', $instrumentation_id)
            ->where('score_count', 21)
            ->get();

        foreach($scoresummaries AS $scoresummary){

            if($scoresummary->score_total <= $cutoff){

                $scoresummary->update(['result' => $results[$ensemble_id]]);
            }else{

                $scoresummary->update(['result' => 'n/a']);
            }
        }

    }
}
