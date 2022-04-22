<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use Illuminate\Support\Facades\App;

class ScoretrackingByAdjudicatorController extends Controller
{
    public function index(Eventversion $eventversion)
    {
        $service = new \App\Services\TableAdjudicatorScoretrackingService($eventversion);

        $table = $service->table();

        return view('eventadministration.scoretrackingbyadjudicators.index',compact('eventversion', 'table'));
    }
}
