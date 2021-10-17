<?php

namespace App\Traits;

use App\Models\Score;
use App\Models\Scoringcomponent;
use App\Models\Userconfig;
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

    public function completedAdjudicationsByInstrumentation(\App\Models\Instrumentation $instrumentation)
    {
        $eventversion = \App\Models\Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $scorecomponents = Scoringcomponent::where('eventversion_id', $eventversion->id)->get();
        $countcomponents = ($scorecomponents->count() * $eventversion->eventversionconfig->judge_count);

        $scoresummary = new \App\Models\Scoresummary;
//dd($scoresummary->where('eventversion_id', $eventversion->id)->where('score_count', $countcomponents)->where('instrumentation_id', 63)->get());
        return $scoresummary->where('eventversion_id', $eventversion->id)
            ->where('score_count', $countcomponents)
            ->where('instrumentation_id', $instrumentation->id)
            ->get();
    }

}
