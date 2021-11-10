<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scoresummary extends Model
{
    use HasFactory;

    protected $fillable = ['result'];

    protected $with = ['registrant'];

    public function registrant()
    {
        return $this->belongsTo(Registrant::class);
    }

    public function registrantScore(\App\Models\Registrant $registrant)
    {
        return $this->where('registrant_id', $registrant->id)
            ->value('score_total');
    }

    public function registrantResult(\App\Models\Registrant $registrant)
    {
        return $this->where('registrant_id', $registrant->id)
            ->value('result');
    }

    /**
     * Return array of raw scores ordered by:
     *  - judge count
     *  - filecontentytype
     *  - scoringcomponent,
     * i.e. first judge, first filecontenttype, first scoringcomponent,
     *      first judge, first filencontenttype, second scoringcomponent
     * regardless of how many judges actually participate per filecontenttype, scoringcomponents
     *
     * @return array
     */
    public function reportingDetails()
    {
        $eventversion = Eventversion::find($this->eventversion_id);
        $scoringcomponents = $eventversion->scoringcomponents;
        $score = new Score;

        $scores = [];

        for($i = 0; $i < $eventversion->eventversionconfig->judge_count; $i++) {

            for ($j = 0; $j < $scoringcomponents->count(); $j++) {

                /**
                 * @todo Adjudicator reference in Score s/b adjudicators->id, not user_Id
                 */

               $scores[] = $score->registrantScoringcomponentScoreByAdjudicatorRank(
                   Registrant::find($this->registrant_id),
                   $scoringcomponents[$j],
                   ($i + 1));
            }
        }

        return $scores;
    }
}
