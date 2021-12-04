<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use Illuminate\Http\Request;

class TimeslotstudentController extends Controller
{
    /**
     * @param \App\Models\Eventversion $eventversion
     */
    public function index(Eventversion $eventversion)
    {

        return view('registrationmanagers.timeslots.students.index',
        [
            'eventversion' => $eventversion,
            'registrants' => $eventversion->registrantsByTimeslotSchoolStudent(),
        ]);
    }
}
