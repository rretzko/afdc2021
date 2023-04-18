<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Exports\RegistrantsExport;
use App\Exports\RegistrantsRosterExport;
use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Guardian;
use App\Models\Instrumentation;
use App\Models\Phone;
use App\Models\Phonetype;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $selectRegistrants = $this->selectRegistrants($eventversion);

        return view('registrationmanagers.registrantdetails.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'selectRegistrants' => $selectRegistrants,
            'targetinstrumentation' => NULL,
            //'instrumentations' => $eventversion->instrumentations(),
           // 'registrationactivity' => new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]),
            'navInstrumentations' => $this->navInstrumentations($eventversion),
            'targetRegistrant' => NULL,
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
        $navInstrumentations = $this->navInstrumentations($eventversion);
        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);
        $registrants = $registrationactivity->registrantsBySchoolNameFullnameAlpha($instrumentation);
        $registrantsArray = $registrationactivity->registrantsBySchoolNameFullnameAlphaArray($instrumentation);
        $selectRegistrants = $this->selectRegistrants($eventversion);
Log::info(__CLASS__ . ': ' . __METHOD__);
        return view('registrationmanagers.registrantdetails.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'targetinstrumentation' => $instrumentation,
            'navInstrumentations' => $navInstrumentations,
            'registrants' => $registrants,
            //'registrationactivity' => $registrationactivity,
            'registrantsArray' => $registrantsArray,
            'selectRegistrants' => $this->selectRegistrants($eventversion),
        ]);
    }

    /**
     * @param Request $request
     * @param Eventversion $eventversion
     * @return void
     */
    public function showSingleRegistrant(Request $request, Eventversion $eventversion)
    {
        $inputs = $request->validate(
            [
                'id' => ['required','min:1','exists:registrants,id'],
            ]
        );

        $bladepath = 'x-registrantdetails.registrantdetail';
        $navInstrumentations = $this->navInstrumentations($eventversion);
        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);
        //$registrants = $registrationactivity->registrantsBySchoolNameFullnameAlpha($instrumentation);
        //$registrantsArray = $registrationactivity->registrantsBySchoolNameFullnameAlphaArray($instrumentation);
        $selectRegistrants = $this->selectRegistrants($eventversion);

        return view('registrationmanagers.registrantdetails.index',[
            'bladepath' => $bladepath,
            'eventversion' => $eventversion,
            'targetinstrumentation' => NULL,
            'navInstrumentations' => $navInstrumentations,
            //'registrants' => $registrants,
            //'registrationactivity' => $registrationactivity,
            //'registrantsArray' => $registrantsArray,
            'selectRegistrants' => $this->selectRegistrants($eventversion),
            'targetRegistrant' => $this->targetRegistrant($inputs['id']),
        ]);
    }

    public function updateSingleRegistrant(Request $request)
    {
        $inputs = $request->validate(
            [
                'id' => ['required','exists:registrants,id'],
                'guardianId' => ['required', 'exists:users,id'],
                'first' => ['required','string'],
                'middle' => ['nullable', 'string'],
                'last' => ['required','string'],
                'instrumentation_id' => ['required','exists:instrumentations,id'],
                'phoneHome' => ['nullable','string'],
                'phoneMobile' => ['nullable','string'],
                'guardianHome' => ['nullable','string'],
                'guardianMobile' => ['nullable','string'],
                'guardianWork' => ['nullable','string'],
            ]
        );

        $registrant = Registrant::find($inputs['id']);
        $student = $registrant->student;
        $person = $student->person;
        $guardian = Guardian::find($inputs['guardianId']);

        //update Person
        $person->update(
            [
                'first' => $inputs['first'],
                'middle' => $inputs['middle'],
                'last' => $inputs['last'],
            ]
        );

        //update Registrant
        $registrant->update(
            [
                'programname' => $person->fullName(),
            ]
        );
        $registrant->instrumentations()->sync($inputs['instrumentation_id']);

        //update Phones
        $this->updatePhone('phoneHome', $inputs, $person->user_id);
        $this->updatePhone('phoneMobile', $inputs, $person->user_id);
        $this->updatePhone('guardianHome', $inputs, $guardian->user_id);
        $this->updatePhone('guardianMobile', $inputs, $guardian->user_id);
        $this->updatePhone('guardianWork', $inputs, $guardian->user_id);


        session()->flash('success',$person->fullName() . "'s record is updated.");

        return $this->index(Eventversion::find($registrant->eventversion_id));

    }

    private function updatePhone(string $type, array $inputs, int $user_id)
    {
        $phoneTypes = [
            'guardianHome' => Phonetype::PHONE_GUARDIAN_HOME,
            'guardianMobile' => Phonetype::PHONE_GUARDIAN_MOBILE,
            'guardianWork' => Phonetype::PHONE_GUARDIAN_WORK,
            'phoneHome' => Phonetype::STUDENT_HOME,
            'phoneMobile' => Phonetype::STUDENT_MOBILE,
        ];
        if($inputs[$type]) {
            $phone = Phone::where('user_id', $user_id)
                ->where('phonetype_id', $phoneTypes[$type])
                ->first();
            if($phone && $phone->id) {
                $phone->phone = $inputs[$type];
                $phone->save();
            }else{

                Phone::create(
                    [
                        'user_id' => $user_id,
                        'phonetype_id' => $phoneTypes[$type],
                        'phone' => $inputs[$type],
                    ],
                    []
                );
            }
        }
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

    private function selectRegistrants(Eventversion $eventversion): Collection
    {
        return Registrant::where('eventversion_id', $eventversion->id)
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->get()
            ->map->only(['id','user_id','fullNameAlpha'])
            ->sortBy('fullNameAlpha');

    }

    /**
     * @param int $id
     * @return array
     */
    private function targetRegistrant(int $id): array
    {
        $registrant = Registrant::find($id);
        $student = $registrant->student;
        $guardian = $student->guardians->first();

        $a = [
            'id' => $id,
            'fullNameAlpha' => $registrant->fullNameAlpha,
            'first' => $student->person->first,
            'middle' => $student->person->middle,
            'last' => $student->person->last,
            'instrumentation_id' => $registrant->instrumentations->first()->id,
            'phoneHome' => $student->phoneHome->id ? $student->phoneHome->phone : '',
            'phoneMobile'=> $student->phoneMobile->id ? $student->phoneMobile->phone : '',
            'guardianId' => $guardian->user_id,
            'guardianName' => $guardian->fullNameAlpha,
            'guardianHome' => $guardian->phoneHome->id ? $guardian->phoneHome->phone : '',
            'guardianMobile' => $guardian->phoneMobile->id ? $guardian->phoneMobile->phone : '',
            'guardianWork' => $guardian->phoneWork->id ? $guardian->phoneWork->phone : '',
        ];

        return $a;
    }
}
