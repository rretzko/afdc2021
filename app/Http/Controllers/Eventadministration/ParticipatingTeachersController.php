<?php

namespace App\Http\Controllers\Eventadministration;

use App\Exports\ParticipatingTeachersExport;
use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Userconfig;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ParticipatingTeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $participatingteachers = (($eventversion->event->id === 11) || ($eventversion->event->id === 12) //sjcda
            || ($eventversion->event->id === 19) //nj all-shore
            || ($eventversion->event->id === 25)) //morris area
            ? $eventversion->participatingTeachersEsignature
            : $eventversion->participatingTeachers;

        return view('eventadministration.participatingteachers.index',
            [
                'eventversion' => $eventversion,
                'participatingteachers' => $participatingteachers,
            ]
        );
    }

    public function export(Eventversion $eventversion)
    {
        $export = new ParticipatingTeachersExport($eventversion);

        $datetime = date('Ynd_Gis');

        return Excel::download($export, 'participatingTeachers_'.$datetime.'.csv');
    }
}
