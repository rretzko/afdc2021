<?php

namespace App\Traits;

use App\Models\Scoringcomponent;

trait IncompleteAdjudicationsTrait
{
    public function IncompleteAdjudications(\App\Models\Eventversion $eventversion)
    {
        $scorecomponents = Scoringcomponent::where('eventversion_id', $eventversion->id)->get();
        $countcomponents = ($scorecomponents->count() * $eventversion->eventversionconfig->judge_count);

        $scoresummary = new \App\Models\Scoresummary;

        return $scoresummary->where('eventversion_id', $eventversion->id)
            ->where('score_count', $countcomponents)
            ->get();
    }

    public function incompleteAdjudicationsByInstrumentation(
        \App\Models\Eventversion $eventversion, \App\Models\Instrumentation $instrumentation)
    {
        $scorecomponents = Scoringcomponent::where('eventversion_id', $eventversion->id)->get();
        $countcomponents = ($scorecomponents->count() * $eventversion->eventversionconfig->judge_count);

        $scoresummary = new \App\Models\Scoresummary;

        return $scoresummary->where('eventversion_id', $eventversion->id)
            ->where('score_count', '<', $countcomponents)
            ->where('instrumentation_id', $instrumentation->id)
            ->get();
    }

}
