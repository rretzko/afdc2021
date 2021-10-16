<?php

namespace App\Traits;

use App\Models\Score;
use App\Models\Scoringcomponent;
use Illuminate\Support\Facades\DB;

trait CompletedAdjudicationsTrait
{
    public function completedAdjudications(\App\Models\Eventversion $eventversion)
    {
        $scorecomponents = Scoringcomponent::where('eventversion_id', $eventversion->id)->get();
        $countcomponents = ($scorecomponents->count() * $eventversion->eventversionconfig->judge_count);

        $scoresummary = new \App\Models\Scoresummary;

        return $scoresummary->where('eventversion_id', $eventversion->id)
            ->where('score_count', $countcomponents)
            ->get();


    }

}
