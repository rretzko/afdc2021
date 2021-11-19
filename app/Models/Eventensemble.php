<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventensemble extends Model
{
    use HasFactory;

    public function instrumentations()
    {
        $eventensembletype = Eventensembletype::find($this->eventensembletype_id);

        return $eventensembletype->instrumentations()->get();
    }

    public function acceptanceStatus(\App\Models\Eventversion $eventversion, \App\Models\Registrant $registrant)
    {
        $names = [20 => 'MX', 21 => 'TB'];

        $instrumentation = $registrant->instrumentations->first();
        $scoresummary = \App\Models\Scoresummary::where('registrant_id', $registrant->id)->first();
        $totalscore = ($scoresummary) ? $scoresummary->score_total : 0;
        $countscore = ($scoresummary) ? $scoresummary->score_count : 0;
        $countscoreset = ($eventversion->eventversionconfig->judge_count * \App\Models\Scoringcomponent::where('eventversion_id', $eventversion->id)->get()->count());

        $cutoffs = [];

        foreach($names AS $ensembleid => $label) {

            $cutoffs[] = \App\Models\Eventensemblecutoff::where('eventversion_id', $eventversion->id)
                    ->where('eventensemble_id', $ensembleid)
                    ->where('instrumentation_id', $instrumentation->id)
                    ->first()->cutoff ?? 0;
        }

        if(! $countscore){ return 'n/s';}

        if($countscore < $countscoreset){
            $this->updateResult($eventversion, $registrant,'inc');
        }elseif($totalscore <= $cutoffs[0]){
            $this->updateResult($eventversion, $registrant,  $names[20]);
        }elseif($totalscore <= $cutoffs[1]){
            $this->updateResult($eventversion, $registrant, $names[21]);
        }else{
            $this->updateResult($eventversion, $registrant, 'n/a');
        }

        return $scoresummary->result;
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 1. Identify accepted registrants
     * 2. Identify unique current schools for #1
     * 3. Identify teacher(s) in #2 who is a member of $this->event->organization
     *
     * @todo register eventensemble_id in scoresummaries for accepted students OR
     *       create a new pivot table to link registrants_id to eventensemble_id with status OR
     *       create a participants model with teacher/student switch and status
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Support\Collection
     */
    public function participatingTeachers(Eventversion $eventversion)
    {
        $organization_id = $this->event->organization->id;

        //#1 Identify Registrants
        $ss = Scoresummary::where('eventversion_id', $eventversion->id)
            ->where('result','acc')
            ->pluck('registrant_id');

        $registrants = Registrant::find($ss);

        //#2 Identify Schools
        $schools = [];
        foreach($registrants AS $registrant){

            if(! array_key_exists($registrant->student->currentSchool->id, $schools)){

                $schools[$registrant->student->currentSchool->id] = $registrant->student->currentSchool;
            }
        }

        //#3 Identify Teachers
        $teachers = [];
        foreach($schools AS $school){

            foreach($school->teachers AS $teacher){

                $membership = Membership::where('user_id', $teacher->user_id)
                    ->where('organization_id', $organization_id)
                    ->first();

                if($membership && (! array_key_exists($teacher->user_id, $teachers))){

                    $teachers[$teacher->user_id] = $teacher;
                }
            }
        }

        return collect($teachers)->sortBy('person.last');
    }

    private function updateResult($eventversion, $registrant, $value)
    {
        \App\Models\Scoresummary::updateOrCreate(
            [
                'eventversion_id' => $eventversion->id,
                'registrant_id' => $registrant->id,
            ],
            [
                'result' => $value,
            ],
        );
    }
}
