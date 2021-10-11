<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class ParticipatingteachersController extends Controller
{
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.participatingteachers.index',
            [
                'eventversion' => $eventversion,
                'participatingteacher' => $eventversion->participatingteacher(),
            ]);
    }
}
