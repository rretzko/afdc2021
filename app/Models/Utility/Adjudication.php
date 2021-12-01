<?php

namespace App\Models\Utility;

use App\Models\Instrumentation;
use App\Models\Score;
use App\Models\Scoringcomponent;
use App\Traits\CompletedAdjudicationsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Adjudication extends Model
{
    use HasFactory, CompletedAdjudicationsTrait;

    protected $fillable = ['eventversion'];

    private $completes;
    private $eventversion;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->eventversion = $attributes['eventversion'];

        $this->init();
    }

    public function grandtotalByInstrumentation(Instrumentation $instrumentation)
    {
        $instrumentation_id = $instrumentation->id;
        $segment = collect();

        //strip $instrumentation match out of population of completed auditions
        foreach($this->completes AS $scoresummary){

            if($scoresummary->instrumentation_id === $instrumentation_id){

                $segment->push($scoresummary);
            }
        }
        return ($this->eventversion->eventversionconfig->bestscore === 'asc')
            ? $segment->sortBy('score_total')
            : $segment->sortByDesc('score_total');
    }

    private function init()
    {
        //all scoring components used by $this->eventversion
        $scoringcomponents = Scoringcomponent::where('eventversion_id', $this->eventversion->id)->get();

        //count of components * # of judges per registrant
        $countcomponents = ($scoringcomponents->count() * $this->eventversion->eventversionconfig->judge_count);

        $this->completes = $this->completedAdjudications($this->eventversion);
    }
}
