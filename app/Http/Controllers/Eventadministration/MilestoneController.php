<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Utility\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index(Eventversion $eventversion)
    {
        $milestone = new Milestone($eventversion);

        $inviteds = $milestone::invitedTeachers();

        return view('eventadministration.milestones.index',
            compact('eventversion', 'inviteds')
        );
    }
}
