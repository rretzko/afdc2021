<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Emailtype;
use App\Models\Eventversion;
use App\Models\Nonsubscriberemail;
use App\Models\Person;
use App\Models\Phone;
use App\Models\Phonetype;
use App\Models\Registrant;
use App\Models\School;
use App\Models\Userconfig;
use App\Traits\CountiesTrait;
use Illuminate\Http\Request;

class RegistrantschoolController extends Controller
{
    use CountiesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Eventversion $eventversion
     * @param  School $school
     * @return \Illuminate\Http\Response
     */
    public function show(Eventversion $eventversion, School $school)
    {
        return view('registrationmanagers.registrants.school.show',
            [
                'school' => $school,
                //'registrants' => $eventversion->registrantsForSchool($school),
                'counties' => $this->geostateCounties(),
                'eventversion' => $eventversion,
                'mycounties' => $this->userCounties(auth()->id(), $eventversion->id),
                'toggle' => Userconfig::getValue('counties', auth()->id()),
                'registrant' => NULL,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\Eventversion $eventversion
     * @param App\Models\School $school
     * @param App\Models\Registrant $registrant
     * @return \Illuminate\Http\Response
     */
    public function edit(Eventversion $eventversion, School $school, Registrant $registrant)
    {
        return view('registrationmanagers.registrants.school.show',
            [
                'school' => $school,
                //'registrants' => $eventversion->registrantsForSchool($school),
                'counties' => $this->geostateCounties(),
                'eventversion' => $eventversion,
                'mycounties' => $this->userCounties(auth()->id(), $eventversion->id),
                'toggle' => Userconfig::getValue('counties', auth()->id()),
                'registrant' => $registrant,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @param Request $request
     * @param $id //registrant_id
     */
    public function updateEmergencyContact(Request $request, $id)
    {
        $registrant = Registrant::find($id);
        $student = $registrant->student;

        $input = $request->validate([
            'email_student_personal' => ['nullable', 'email'],
            'email_student_school' => ['nullable', 'email'],
            'guardian_id' => ['required', 'numeric'],
            'first' => ['required', 'string'],
            'last' => ['required', 'string'],
            'email_guardian_primary' => ['nullable', 'email'],
            'email_guardian_alternate' => ['nullable', 'email'],
            'phone_guardian_mobile' => ['nullable', 'string'],
            'phone_guardian_work' => ['nullable', 'string'],
            'phone_guardian_home' => ['nullable', 'string'],
        ]);

        //email_student_personal
        Nonsubscriberemail::updateOrCreate(
            [
                'user_id' => $student->user_id,
                'emailtype_id' => Emailtype::STUDENT_PERSONAL,
            ],
            [
                'email' => $input['email_student_personal'],
            ]
        );

        //email_student_school
        Nonsubscriberemail::updateOrCreate(
            [
                'user_id' => $student->user_id,
                'emailtype_id' => Emailtype::STUDENT_SCHOOL,
            ],
            [
                'email' => $input['email_student_school'],
            ]
        );

        //email_guardian_primary
        Nonsubscriberemail::updateOrCreate(
            [
                'user_id' => $input['guardian_id'],
                'emailtype_id' => Emailtype::GUARDIAN_PRIMARY
            ],
            [
                'email' => $input['email_guardian_primary'],
            ]
        );

        //email_guardian_alternate
        Nonsubscriberemail::updateOrCreate(
            [
                'user_id' => $input['guardian_id'],
                'emailtype_id' => Emailtype::GUARDIAN_ALTERNATE
            ],
            [
                'email' => $input['email_guardian_alternate'],
            ]
        );

        //update guardian name
        if($input['guardian_id']){

            $person = Person::find($input['guardian_id']);
            $person->first = $input['first'];
            $person->last = $input['last'];
            $person->save();
        }

        //phone_guardian_mobile
        Phone::updateOrCreate(
            [
                'user_id' => $input['guardian_id'],
                'phonetype_id' => Phonetype::PHONE_GUARDIAN_MOBILE,
            ],
            [
                'phone' => $input['phone_guardian_mobile'],
            ]
        );

        //phone_guardian_work
        Phone::updateOrCreate(
            [
                'user_id' => $input['guardian_id'],
                'phonetype_id' => Phonetype::PHONE_GUARDIAN_WORK,
            ],
            [
                'phone' => $input['phone_guardian_work'],
            ]
        );

        //phone_guardian_home
        Phone::updateOrCreate(
            [
                'user_id' => $input['guardian_id'],
                'phonetype_id' => Phonetype::PHONE_GUARDIAN_HOME,
            ],
            [
                'phone' => $input['phone_guardian_home'],
            ]
        );

        return $this->show(Eventversion::find(Userconfig::getValue('eventversion', auth()->id())),
            School::find($registrant->school_id));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
