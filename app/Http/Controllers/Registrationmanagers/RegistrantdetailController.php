<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Exports\RegistrantsExport;
use App\Exports\RegistrantsRosterExport;
use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Instrumentation;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            //'instrumentations' => $eventversion->instrumentations(),
           // 'registrationactivity' => new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]),
            'navInstrumentations' => $this->navInstrumentations($eventversion),
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

    /**
     * download a pdf of ALL participants
     *
     * @param  \App\Models\Eventversion $eventversion
     */
    public function csvRegistrationAll(Eventversion $eventversion)
    {
        $download = new RegistrantsRosterExport($eventversion);

        return Excel::download($download, 'registrants_roster_'.strtotime('NOW').'.csv');
    }

    public function changeVoicePart(Registrant $registrant)
    {
        $eventversion = Eventversion::find($registrant->eventversion_id);
        $bladepath = 'x-registrantdetails.index';

        return view('registrationmanagers.registrantdetails.changeVoicePart',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'targetinstrumentation' => NULL,
            'instrumentations' => $eventversion->instrumentations(),
            'registrationactivity' => new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]),
            'registrant' => $registrant,
        ]);
    }

    private function navInstrumentations(Eventversion $eventversion)
    {
        $a = [];
        $eventversionId = $eventversion->id;

        foreach($eventversion->instrumentations()->pluck('descr','id') AS $id => $descr){

            $a[$id] = [$descr, $this->countRegistrants($eventversionId, $id)];
        }

        return $a;
    }

    private function countRegistrants($eventversionId, $instrumentationId): int
    {
        return DB::table('registrants')
            ->join('instrumentation_registrant', 'registrants.id','=','instrumentation_registrant.registrant_id')
            ->where('registrants.eventversion_id', $eventversionId)
            ->where('registrants.registranttype_id', Registranttype::REGISTERED)
            ->where('instrumentation_id', $instrumentationId)
            ->count('registrants.id');
    }

    public function updateVoicePart(Request $request, Registrant $registrant)
    {
        $inputs = $request->validate(
            [
                'instrumentation_id' => ['required','numeric','min:63','max:70'],
            ]
        );

        $instrumentation = Instrumentation::find($inputs['instrumentation_id']);

        $registrant->instrumentations()->sync($instrumentation->id);

        return $this->index(Eventversion::find(Userconfig::getValue('eventversion', auth()->id())));
    }
}
