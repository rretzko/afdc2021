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

    public function acceptanceStatus(\App\Models\Registrant $registrant)
    {
        $names = [20 => 'MX', 21 => 'TB'];

        $instrumentation = $registrant->instrumentations->first();
        $scoresummary = \App\Models\Scoresummary::where('registrant_id', $registrant->id)->first();
        $totalscore = ($scoresummary) ? $scoresummary->score_total : 0;
        $countscore = ($scoresummary) ? $scoresummary->score_count : 0;
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $countscoreset = ($eventversion->eventversionconfig->judge_count * \App\Models\Scoringcomponent::where('eventversion_id', $eventversion->id)->get()->count());

        $cutoffs = [];

        foreach($names AS $ensembleid => $label) {

            $cutoffs[] = \App\Models\Eventensemblecutoff::where('eventversion_id', Userconfig::getValue('eventversion', auth()->id()))
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
