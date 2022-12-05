<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Exports\RegistrantsExport;
use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Instrumentation;
use App\Models\Utility\RegistrationActivity;
use Maatwebsite\Excel\Facades\Excel;

class RegistrantdetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $bladepath = 'x-registrantdetails.index';
        return view('registrationmanagers.registrantdetails.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'targetinstrumentation' => NULL,
            'instrumentations' => $eventversion->instrumentations(),
            'registrationactivity' => new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eventversion $eventversion
     * @param  \App\Models\Instrumentation $instrumentation
     * @return \Illuminate\Http\Response
     */
    public function show(Eventversion $eventversion, Instrumentation $instrumentation)
    {
        $bladepath = 'x-registrantdetails.registrantdetail';
        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        return view('registrationmanagers.registrantdetails.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'targetinstrumentation' => $instrumentation,
            'instrumentations' => $eventversion->instrumentations(),
            'registrants' => $registrationactivity->registrantsBySchoolNameFullnameAlpha($instrumentation),
            'registrationactivity' => $registrationactivity,
        ]);
    }

    /**
     * download a pdf
     *
     * @param  \App\Models\Eventversion $eventversion
     * @param  \App\Models\Instrumentation $instrumentation
     */
    public function csv(Eventversion $eventversion, Instrumentation $instrumentation)
    {
        $download = new RegistrantsExport($eventversion, $instrumentation);

        return Excel::download($download, 'registrants_'.strtotime('NOW').'.csv');
    }

    /**
     * download a pdf of ALL participants
     *
     * @param  \App\Models\Eventversion $eventversion
     */
    public function csvAll(Eventversion $eventversion)
    {
        $download = new RegistrantsExport($eventversion);

        return Excel::download($download, 'registrants_'.strtotime('NOW').'.csv');
    }
}
