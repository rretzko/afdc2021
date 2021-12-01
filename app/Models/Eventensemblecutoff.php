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

        foreach ($this->CompletedAdjudications($this->eventversion) as $scoresummary) {

            $cntr += (($scoresummary->instrumentation_id === $instrumentationid) &&
                ($scoresummary->score_total > $startfrom) &&
                ($scoresummary->score_total <= $cutoff));
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
        dd(Registrant::find($this->completedAdjudications($this->eventversion)[0][0]->registrant_id)->student->person->fullnameAlpha());
    }

/** END OF PUBLIC FUNCTIONS **************************************************/
    private function startFrom(\App\Models\Eventensemble $eventensemble, Instrumentation $instrumentation)
    {
        $startfrom = 0;

        foreach($this->eventversion->event->eventensembles AS $key => $current){

            if($current === $eventensemble){

                return $startfrom;

            }else{

                $startfrom = $this->cutoffByEnsembleInstrumentation($current,$instrumentation);
            }
        }
    }
}
