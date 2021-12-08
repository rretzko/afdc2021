<?php

namespace App\Models;

use App\Traits\CompletedAdjudicationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\In;

class Eventensemblecutoff extends Model
{
    use HasFactory, CompletedAdjudicationsTrait;

    public $eventversion;
    protected $fillable = ['eventversion_id','eventensemble_id', 'instrumentation_id', 'cutoff', 'user_id'];
    private $colors;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->colors = [
            'rgba(0,255,0,.1)', //green
            'rgba(240,255,0,.25)', //yellow
            'rgba(44,130,201,.1)', //blue
        ];
    }

    public function backgroundColorByScoreInstrumentation(\App\Models\Scoresummary $scoresummary, Instrumentation $instrumentation)
    {
        if($this->eventversion->eventversionconfig->alternating_scores){

            return $this->backgroundColorByScoreInstrumentationAlternating($scoresummary, $instrumentation);
        }

        $eventensembles = $this->eventversion->event->eventensembles;

        //cycle through the event comparing $score with cutoff to determine background color
        foreach($eventensembles AS $key => $eventensemble){

            $cutoff = $this::where('eventversion_id', $this->eventversion->id)
                ->where('eventensemble_id', $eventensemble->id)
                ->where('instrumentation_id', $instrumentation->id)
                ->value('cutoff') ?? 0;

            //early exit
            if(! $cutoff){return '';}

            if ($this->eventversion->eventversionconfig->bestscore === 'asc') {
                if ($scoresummary->score_total <= $cutoff) {

                    return $this->colors[$key];
                }
            } else {
                if ($scoresummary->score_total >= $cutoff) {

                    return $this->colors[$key];
                }
            }
        }

        return '';
    }

    public function countAccepted(Eventensemble $eventensemble)
    {
        $cntr = 0;

        foreach($this->eventversion->instrumentations() AS $instrumentation){

            $cntr += $this->countByCutoffEventensembleInstrumentation($eventensemble, $instrumentation);
        }

        return $cntr;
    }

    public function countByCutoffEventensembleInstrumentation(Eventensemble $eventensemble, Instrumentation $instrumentation)
    {
        $cntr = 0;
        $startfrom = $this->startFrom($eventensemble, $instrumentation);
        $cutoff = $this->cutoffByEnsembleInstrumentation($eventensemble,$instrumentation);
        $instrumentationid = $instrumentation->id;
        $bestscore = $this->eventversion->eventversionconfig->bestscore;

        foreach ($this->CompletedAdjudications($this->eventversion) as $scoresummary) {

            if($bestscore === 'asc') {
                $cntr += (($scoresummary->instrumentation_id === $instrumentationid) &&
                    ($scoresummary->score_total > $startfrom) &&
                    ($scoresummary->score_total <= $cutoff));
            }else{
                $cntr += (($scoresummary->instrumentation_id === $instrumentationid) &&
                    ($scoresummary->score_total > $startfrom) &&
                    ($scoresummary->score_total >= $cutoff));
            }
        }

        return $cntr;
    }

    public function cutoffByEnsembleInstrumentation(Eventensemble $eventensemble, Instrumentation $instrumentation)
    {
        return $this::where('eventversion_id', $this->eventversion->id)
        ->where('eventensemble_id', $eventensemble->id)
        ->where('instrumentation_id', $instrumentation->id)
        ->value('cutoff');
    }

    public function eventensembleLegendColor(Eventensemble $eventensemble)
    {
        foreach($this->eventversion->event->eventensembles AS $key => $testensemble)
        {
            if($eventensemble === $testensemble){

                return $this->colors[$key];
            }
        }

        return '';
    }

    public function roster()
    {
        //dd(Registrant::find($this->completedAdjudications($this->eventversion)[0][0]->registrant_id)->student->person->fullnameAlpha());
    }

/** END OF PUBLIC FUNCTIONS **************************************************/

    private function backgroundColorByScoreInstrumentationAlternating(\App\Models\Scoresummary $scoresummary, Instrumentation $instrumentation)
    {
        $eventensembles = $this->eventversion->event->eventensembles;

        $cutoff = $this::where('eventversion_id', $this->eventversion->id)
                ->where('eventensemble_id', $eventensembles->first()->id)
                ->where('instrumentation_id', $instrumentation->id)
                ->value('cutoff') ?? 0;

        //early exits
        if(! $cutoff) {return '';}
        if(
            (($this->eventversion->eventversionconfig->bestscore === 'asc') && ($scoresummary->score_total > $cutoff)) ||
            (($this->eventversion->eventversionconfig->bestscore === 'desc') && ($scoresummary->score_total < $cutoff))
        ) {
            return '';
        }
        //if alternate ensemble contains $instrumentation_id, determine alternating color,
        //else use primary color

        if(! $eventensembles[1]->instrumentations()->contains($instrumentation)){
            return $this->colors[0];
        }

        //low-to-high golf scores
        $uniquescores = $scoresummary->uniqueScoresByEventversionInstrumentation($this->eventversion, $instrumentation);

        //high-to-low bowling scores
        if($this->eventversion->eventversionconfig->bestscore === 'desc'){ rsort($uniquescores);}

        $uniquescoresswap = array_flip($uniquescores);

        //get the key of the cut-off score
        $keyorder = $uniquescoresswap[$scoresummary->score_total];

        return $this->colors[( $keyorder % 2)];
    }

    private function startFrom(\App\Models\Eventensemble $eventensemble, Instrumentation $instrumentation)
    {
        $startfrom = 0;

        foreach($this->eventversion->event->eventensembles AS $current){

            if($current === $eventensemble){

                return $startfrom;

            }else{

                $startfrom = $this->cutoffByEnsembleInstrumentation($current,$instrumentation);
            }
        }
    }
}
