<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dashboard = new DashboardService;
        $newevent = new Event;
        $event = $newevent->currentEventversion();

        return view('eventadministration.eventversions.eventversion.dashboard.index',
            compact('dashboard', 'event')
        );
    }
}
