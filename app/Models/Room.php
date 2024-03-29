<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['descr', 'eventversion_id', 'order_by','tolerance'];

    protected $with = ['adjudicators'];

    public function adjudicators()
    {
        return $this->hasMany(Adjudicator::class)
            ->orderBy('rank');
    }

    public function filecontenttypes()
    {
        return $this->belongsToMany(Filecontenttype::class)
            ->orderBy('order_by');
    }

    public function instrumentations()
    {
        return $this->belongsToMany(Instrumentation::class);
    }

    public function auditionees()
    {
        $instrumentations = $this->instrumentations;

        $r = Registrant::query()
            ->where('eventversion_id', Userconfig::getValue('eventversion', auth()->id()))
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->get();

        $a = $r->filter(function($registrant) use($instrumentations){
                return $instrumentations->contains($registrant->instrumentations->first());
            });

        return $a;
    }

    public function auditioneesByTime()
    {
        $a = [];
        foreach($this->auditionees() AS $registrant){

            $armytime = Timeslot::where('eventversion_id', Userconfig::getValue('eventversion', auth()->id()))
                ->where('school_id',$registrant->student->person->user->schools->first()->id)
                ->value('armytime');

            $a[] = [
                'timestamp' => $armytime,
                'registrant_id' => $registrant->id,
                'registrant' => $registrant,
            ];
        };
        sort($a);

        return array_column($a, 'registrant');
    }

    public function auditioneesCount()
    {
        $instrumentationids = $this->instrumentations->pluck('id')->toArray();

        return Registrant::with('instrumentations')
            ->join('instrumentation_registrant','instrumentation_registrant.registrant_id','=','registrants.id')
            ->whereIn('instrumentation_registrant.instrumentation_id',  $instrumentationids)
            ->where('registrants.eventversion_id', $this->eventversion_id)
            ->where('registrants.registranttype_id', Registranttype::REGISTERED)
            ->count('registrants.id');
    }

    public function auditioneesScoredCountByAdjudicator($adjudicator)
    {
        $instrumentationids = $this->instrumentations->pluck('id')->toArray();

        return Registrant::with('instrumentations')
            ->join('instrumentation_registrant','instrumentation_registrant.registrant_id','=','registrants.id')
            ->join('scores', 'scores.registrant_id', '=', 'registrants.id')
            ->whereIn('instrumentation_registrant.instrumentation_id',  $instrumentationids)
            ->where('registrants.eventversion_id', $this->eventversion_id)
            ->where('scores.user_id', '=', $adjudicator->user_id)
            ->distinct('registrants.id')
            ->count('registrants.id');
    }

    public function createFakeScores(int $eventversion_id): int
    {
        $score_count = ($this->scoringcomponents()->count() * $this->adjudicators->count());
        $score_total = 0;

        foreach($this->auditionees() AS $auditionee){

            foreach($this->adjudicators AS $adjudicator){

                foreach($this->scoringcomponents() AS $scoringcomponent){

                    $minmax = [$scoringcomponent->bestscore, $scoringcomponent->worstscore];

                    $score = (random_int(min($minmax), max($minmax)) * $scoringcomponent->multiplier);
                    $score_total += $score;

                    Score::updateOrCreate(
                        [
                            'registrant_id' => $auditionee->id,
                            'eventversion_id' => $eventversion_id,
                            'user_id' => $adjudicator->user_id,
                            'scoringcomponent_id' => $scoringcomponent->id,
                        ],
                        [
                            'score' => $score,
                            'proxy_id' => $adjudicator->user_id,
                        ]
                    );
                }
            }

            $this->updateScoreSummary($auditionee->id, $eventversion_id, $auditionee->instrumentations->first()->id);

            $score_total = 0;
        }

        return $score_count;
    }

    public function scoringcomponents()
    {
        $scoringcomponents = collect();

        foreach($this->filecontenttypes AS $filecontenttype){

            $scoringcomponents = $scoringcomponents
                ->merge($filecontenttype->scoringcomponents->where('eventversion_id', $this->eventversion_id));
        }

        return $scoringcomponents;
    }

    private function updateScoreSummary(int $auditionee_id, int $eventversion_id, int $instrumentation_id): void
    {
        Scoresummary::updateOrCreate(
            [
                'eventversion_id' => $eventversion_id,
                'registrant_id' => $auditionee_id,
                'instrumentation_id' => $instrumentation_id,
            ],
            [
                'score_total' => Score::where('registrant_id',$auditionee_id)->sum('score'),
                'score_count' => Score::where('registrant_id',$auditionee_id)->count('score'),
            ]
        );
    }
}
