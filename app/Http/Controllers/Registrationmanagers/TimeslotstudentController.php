<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use Barryvdh\DomPDF\Facade as PDF;
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

    public function pdf(Eventversion $eventversion)
    {
        $registrants = $eventversion->registrantsByTimeslotSchoolStudent();

        $pdf = PDF::loadView('pdfs.timeslots.timeslotschoolstudent',
        compact('eventversion', 'registrants')
        )->setPaper('letter', 'portrait');

        return $pdf->download('timeslots_'.str_replace(' ', '_',$eventversion->short_name).'.pdf');

    }
}
