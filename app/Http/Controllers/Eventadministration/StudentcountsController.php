<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Teacher;
use App\Models\Userconfig;
use App\Models\Utility\Stats;
use Illuminate\Http\Request;

class StudentcountsController extends Controller
{
    public function index()
    {
        return view('eventadministration.studentcounts.index',
            [
                'applicants' => Stats::applicationCount(),
                'applicantsbyinstrumentation' => Stats::applicationCountsByInstrumentation(),
                'eventversion' => Eventversion::find(Userconfig::getValue('eventversion', auth()->id())),
                'minrecording' => Stats::minRecordingCount(),
                'minrecordingbyinstrumentation' => Stats::minRecordingCountByInstrumentation(),
                'schools' => Stats::schoolsWithApplicantsCount(),
            ]
        );
    }
}
