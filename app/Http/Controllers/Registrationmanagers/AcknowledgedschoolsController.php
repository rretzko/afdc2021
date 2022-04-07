<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Userconfig;
use App\Models\Utility\Stats;
use App\Traits\CountiesTrait;

class AcknowledgedschoolsController extends Controller
{
    use CountiesTrait;

    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.acknowledgedschools.index',
            [
                'applicants' => Stats::applicationCount(),
                'mycounties' => $this->userCounties(auth()->id(),$eventversion->id),
                'counties' => $this->geostateCounties(),
                'eventversion' => $eventversion,
                'acknowledgedschools' => $eventversion->acknowledgedSchools,
                'toggle' => Userconfig::getValue('counties', auth()->id()),
            ]);
    }
}
