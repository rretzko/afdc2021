<?php

namespace App\Models\Utility;

use App\Models\Eventversion;
use App\Models\Instrumentation;
use App\Models\Payment;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\School;
use App\Models\Schoolpayment;
use App\Models\Student;
use App\Traits\SenioryearTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RegistrationActivity extends Model
{
    use SenioryearTrait;

    public $duplicateregistrants;

    protected $fillable = ['counties', 'eventversion'];

    private $classofs;
    private $counties;
    private $eventversion;
    private $eventversion_id;
    private $eventversion_schools;
    private $payment;
    private $registrants;
    private $schools;
    private $school_id;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->eventversion = $attributes['eventversion'];
        $this->counties = $attributes['counties'];
        $this->classofs = $this->eventversion->classofs();
        $this->duplicateregistrants = collect();
        $this->eventversion_id = $this->eventversion->id;
        $this->eventversion_schools = $this->eventversion->schools();
        $this->payment = new Payment;

        $this->registrants = $this->buildRegistrants();

    }

    /*public function applied(Eventversion $eventversion, School $school)
    {
        $this->setVars($eventversion, $school);

        return $this->appliedRegistrants($this->classofs());
    }*/

    /**
     * Decorator
     * @param School $school
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function appliedCount(School $school)
    {
        return $this->zeroDecorator($this->appliedRegistrantsForSchool($school));
    }

    /**
     * @return Collection
     */
    public function appliedTotal()
    {
        return $this->registrants->filter(function($registrant){
            return $registrant->registranttype_id === Registranttype::APPLIED;
        });
    }

    public function eligible(School $school)
    {
        return $this->eligibleRegistrantsForSchool($school);
    }

    /**
     * Decorator
     * @param School $school
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function eligibleCount(School $school)
    {
        return $this->zeroDecorator($this->eligibleRegistrantsForSchool($school));
    }

    /**
     * @return Collection
     */
    public function eligibleTotal()
    {
        return $this->registrants;
    }

    /*public function registered(Eventversion $eventversion, School $school)
    {
        $this->setVars($eventversion, $school);

        return self::registeredRegistrants($this->classofs());
    }*/

    /**
     * Decorator
     * @param School $school
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function registeredCount(School $school)
    {
        return $this->zeroDecorator($this->registeredRegistrantsForSchool($school));
    }

    /**
     * @return Collection
     */
    public function registeredInstrumentationCount(School $school, Instrumentation $instrumentation)
    {
        return $this->zeroDecorator($this->registeredInstrumentationForSchool($school, $instrumentation));
    }

    /**
     * @return Collection
     */
    public function registeredInstrumentationTotal(Instrumentation $instrumentation)
    {
        return $this->registeredTotal()->filter(function($registrant) use($instrumentation){
            return $registrant->instrumentations->contains($instrumentation);
        });
    }

    /**
     * @return Collection
     */
    public function registeredInstrumentationTotalCount(Instrumentation $instrumentation)
    {
        return $this->registeredTotal()->filter(function($registrant) use($instrumentation){
            return $registrant->instrumentations->contains($instrumentation);
        })->count();
    }

    /**
     * @return Collection
     */
    public function registeredTotal()
    {
        $originals = $this->registrants->filter(function($registrant){
            return $registrant->registranttype_id === Registranttype::REGISTERED;
        });

        $duplicates = $this->duplicateregistrants->filter(function($registrant){
            return $registrant->registranttype_id === Registranttype::REGISTERED;
        });

        $merged = $originals->merge($duplicates);

        return $merged->sortBy('id');
    }

    public function registrantsBySchoolNameFullnameAlpha(Instrumentation $instrumentation)
    {
        $a = [];

        foreach($this->registeredInstrumentationTotal($instrumentation) AS $registrant){

            $a[] = [
                'schoolname' => $registrant->student->currentSchool->name,
                'fullname' => $registrant->student->person->fullnameAlpha(),
                'registrant' => $registrant,
            ];
        }

        sort($a);

        return collect(array_column($a,'registrant'));
    }

    public function registrantsByTimeslotSchoolNameFullnameAlpha(Instrumentation $instrumentation)
    {
        $a = [];

        foreach($this->registeredInstrumentationTotal($instrumentation) AS $registrant){

            $a[] = [
                'armytime' => $registrant->armytime,
                'schoolname' => $registrant->student->currentSchool->name,
                'fullname' => $registrant->student->person->fullnameAlpha(),
                'registrant' => $registrant,
            ];
        }

        sort($a);

        return collect(array_column($a,'registrant'));
    }

    public function registrationfeeDue(School $school)
    {
        return $this->registeredRegistrantsForSchool($school)->count() * $this->eventversion->eventversionconfig->registrationfee;
    }

    public function registrationfeeDueTotal()
    {
        return $this->registeredTotal()->count() * $this->eventversion->eventversionconfig->registrationfee;
    }

    /**
     * This tallies the fees collected and recorded by the director
     * and NOT fees submitted by the director
     *
     * @param School $school
     * @return mixed
     */
    public function registrationfeePaid(School $school)
    {
        return $this->payment->sumBySchool($this->eventversion, $school);
    }

    /**
     * This tallies the fees paid by the Director and recorded by the Registration Manager
     * @param School $school
     * @return mixed
     */
    public function registrationfeePaidBySchool(School $school)
    {
        return Schoolpayment::where('eventversion_id', $this->eventversion->id)
            ->where('school_id', $school->id)
            ->sum('amount');
    }

    /**
     * This tallies payments made by students and recorded by Directors
     * @return mixed
     */
    public function registrationfeePaidTotal()
    {
        return $this->payment->sumByEventversion($this->eventversion, $this->counties);
    }

    /**
     * This tallies payments made by schools and recorded by Registration Manager(s)
     * @return mixed
     */
    public function registrationfeePaidTotalBySchools()
    {
        return Schoolpayment::where('eventversion_id', $this->eventversion->id)
            ->sum('amount');
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

   /* private function appliedRegistrants(array $classofs)
    {
        $registrants = collect();
        $students = $this->confirmEligibleStudentsAreRegistrants($classofs);
        $eventversion_id = $this->eventversion_id;

        $applieds = $students->filter(function($student) use ($eventversion_id){
           return (bool)Registrant::where('user_id', $student->user_id)
               ->where('eventversion_id', $eventversion_id)
               ->where('registranttype_id', Registranttype::APPLIED)
               ->first();
        });

        foreach($applieds AS $student) {

            $registrants->push($student->registrants
                ->where('eventversion_id', $this->eventversion_id)->first());
        }

        return $registrants;
    }*/

    private function appliedRegistrantsForSchool($school)
    {
        $school_id = $school->id;

        return $this->appliedTotal()->filter(function($registrant) use ($school_id){

            return $registrant->school_id === $school_id;
        });
    }

    /**
     * @todo build methodology that prohibits creation of duplicate registrations for unique user_ids
     * AND which prohibits create of registrants for previous schools
     * ex. Middle school->high school student with teacher who works in both; registration should be
     * only created for the age-appropriate AND current school
     * ex. Eventversion covers 8,9,10, student had same teacher for 8,9 in different schools
     * @return Collection
     */
    private function buildRegistrants()
    {
        $registrantids = (count($this->counties))
            ? DB::select(DB::raw("
                SELECT registrants.id FROM registrants,school_user,schools,students
                WHERE registrants.eventversion_id= :eventversion_id
                AND registrants.user_id=school_user.user_id
                AND school_user.school_id=schools.id
                AND schools.county_id IN (".implode(',',$this->counties).")
                AND registrants.user_id=students.user_id
                AND students.classof IN (".implode(',',$this->classofs).")"),
              ['eventversion_id' => $this->eventversion_id,])

            : DB::select(DB::raw("
                SELECT registrants.id
                FROM registrants,school_user,schools,students
                WHERE registrants.eventversion_id= :eventversion_id
                AND registrants.user_id=school_user.user_id
                AND school_user.school_id=schools.id
                AND registrants.user_id=students.user_id
                AND students.classof IN (".implode(',',$this->classofs).")"),
                ['eventversion_id' => $this->eventversion_id,]);

        //array of registrant ids
        $ids = array_map(function($row){
            return $row->id;
        }, $registrantids);

        //array of school ids
        $schoolids = [];
        foreach($this->eventversion_schools AS $school) {
            $schoolids[] = $school->id;
        };
        sort($schoolids);

        $registrants =  Registrant::with('student', 'student.person', 'student.person.user.schools')
            ->whereIn('id', $ids)
            ->whereIn('school_id', $schoolids)
            ->get()
            ->sortBy('person.last');

        //filter out duplicate registrants
        $uniques = collect();
        $userids = [];
        foreach($registrants AS $registrant){
            if(in_array($registrant->user_id, $userids)){

                $this->duplicateregistrants->push($registrant);

            }else{

                $userids[] = $registrant->user_id;

                $uniques->push($registrant);
            }
        }

        return $uniques;
    }

    /*private function classofs()
    {
        return array_map('self::getClassofFromGrade',
            explode(',',$this->eventversion->eventversionconfig->grades));
    }*/

    /*private function confirmEligibleStudentsAreRegistrants(array $classofs)
    {
        $students = $this->eligibleStudents($classofs);

        //ensure that all eligible students have a registrant record
        foreach($students AS $student) {

            $id = self::makeRegistrantId();

            $registrant = Registrant::firstOrCreate(
                [
                    'user_id' => $student->user_id,
                    'eventversion_id' => $this->eventversion_id,
                    'school_id' => $this->school_id,
                ],
                [
                    'id' => $id,
                    'programname' => $student->person->fullName,
                    'registranttype_id' => Registranttype::ELIGIBLE,
                ]
            );

            //ensure that newly created $registrant has its properties
            if($registrant->id){

                $registrant->fresh();

            }else{

                $registrant = Registrant::find($id);
            }

            // if $registrant does not already have an assigned instrumentation,
            // assign the $registrant->student's first instrumentation
            // OR if none exists, assign the first instrumentation for the $eventversion->ensemble
            if (! $registrant->instrumentations->count()){
                $registrant->instrumentations()->attach(self::defaultInstrumentationId($registrant));
            }
        }

        return $students;
    }*/

    /**
     * If no registrant instrumentation currently exists,
     *  a) Choose the first instrumentation for the $registrant->student IF
     *      a.1) that instrumentation exists for the first $eventensemble ELSE
     *  b) Choose the first instrumentation for the $eventensemble
     *
     * @param Registrant $registrant
     * @return int
     */
    /*private function defaultInstrumentationId(Registrant $registrant)
    {
        $eventversion = Eventversion::with('event.eventensembles')->where('id', $this->eventversion_id)->first();

        $eventversioninstrumentations = $eventversion->instrumentations();//$eventversion->eventensembles->first()
            //->eventensembletype()->instrumentations;

        $eventversionfirstinstrumentid = $eventversioninstrumentations->first()->id;

        $registrantinstrumentations = $registrant->student->person->user->instrumentations ?? null;

        $registrantfirstinstrumentid = ($registrantinstrumentations && $registrantinstrumentations->first())
            ? $registrantinstrumentations->first()->id
            : 0;

        return ($registrantinstrumentations && $eventversioninstrumentations->contains($registrantfirstinstrumentid))
                ? $registrantfirstinstrumentid
                : $eventversionfirstinstrumentid;
    }*/

    /**
     * Returns ALL registrants except HIDDEN
     * @param string $search
     * @param array $classofs
     * @return Collection
     */
    /*private function eligibleRegistrants(array $classofs)
    {
        $registrants = collect();
        $students = $this->confirmEligibleStudentsAreRegistrants($classofs);

        foreach($students AS $student) {

            $registrants->push($student->registrants
                ->where('eventversion_id', $this->eventversion_id)->first());
        }

        return $registrants;
    }*/

    private function eligibleRegistrantsForSchool($school)
    {
        $school_id = $school->id;

        return $this->registrants->filter(function($registrant) use ($school_id){

           return $registrant->school_id === $school_id;
        });
    }

    /*private function eligibleStudents(array $classofs, $counties = [])
    {
        if($this->school_id) { //activity for a single school

            $school_id = $this->school_id;

            return Student::with('person', 'person.user.schools', 'registrants')
                ->whereIn('classof', $classofs)
                ->whereHas('person.user.schools', function (Builder $query) use ($school_id) {
                    $query->where('school_id', '=', $school_id);
                })
                ->get()
                ->sortBy('person.last');

        }else{ //eventversion-wide activity

            $registrantids = DB::select(DB::raw("
                SELECT registrants.id FROM registrants,school_user,schools,students
                WHERE registrants.eventversion_id= :eventversion_id
                AND registrants.user_id=school_user.user_id
                AND school_user.school_id=schools.id
                AND schools.county_id IN (".implode(',',$counties).")
                AND registrants.user_id=students.user_id
                AND students.classof IN (".implode(',',$classofs).")"),
                ['eventversion_id' => 65,]);

            $ids = implode(',',array_map(function($row){
                return $row->id;
                }, $registrantids));

            return Registrant::with('student', 'student.person', 'student.person.user.schools')
                ->whereIn('id', explode(',',$ids))
                ->get()
                ->sortBy('person.last');
        }
    }*/

    /*private function getClassofFromGrade($grade) : int
    {
        static $senioryear = null;

        if(is_null($senioryear)){
            $senioryear = $this->senioryear();
        }

        return ($senioryear + (12 - $grade));
    }*/

    /*private static function makeRegistrant(Student $student)
    {dd(__FUNCTION__);
        Registrant::updateOrCreate([
          'id' => self::makeRegistrantId(),
          'user_id' => $student->user_id,
          'eventversion_id' => self::$eventversion_id,
          'school_id' => self::$school_id,
          'programname' => $student->person->fullName,
          'registranttype_id' => Registranttype::ELIGIBLE,
        ]);
    }*/

    /*private function makeRegistrantId() : int
    {
        $id = $this->eventversion_id.rand(1000,9999);//ex.651234

        //if a Registrant is found with $id, continue to generate numbers
        while(Registrant::find($id)){

            $id = $this->eventversion_id.rand(1000,9999);
        }

        //return the unused registrant_id
        return $id;
    }*/

    /*private function registeredRegistrants(array $classofs)
    {
        $registrants = collect();
        $students = $this->confirmEligibleStudentsAreRegistrants($classofs);
        $eventversion_id = $this->eventversion_id;

        $registereds = $students->filter(function($student) use ($eventversion_id){
            return (bool)Registrant::where('user_id', $student->user_id)
                ->where('eventversion_id', $eventversion_id)
                ->where('registranttype_id', Registranttype::REGISTERED)
                ->first();
        });

        foreach($registereds AS $student) {

            $registrants->push($student->registrants
                ->where('eventversion_id', $this->eventversion_id)->first());
        }

        return $registrants;
    }*/

    private function registeredInstrumentationForSchool(School $school, Instrumentation $instrumentation)
    {
        return $this->registeredRegistrantsForSchool($school)->filter(function($registrant) use ($instrumentation){

            return $registrant->instrumentations->contains($instrumentation);
        });
    }


    private function registeredRegistrantsForSchool($school)
    {
        $school_id = $school->id;

        return $this->registeredTotal()->filter(function($registrant) use ($school_id){

            return $registrant->school_id === $school_id;
        });
    }

    /*private function setVars(Eventversion $eventversion, School $school)
    {
        $this->eventversion_id = $eventversion->id;
        $this->eventversion = $eventversion;
        $this->school_id = $school->id;

    }*/

    private function zeroDecorator($collection)
    {
        return ($collection->count()) ?: '<span style="color:lightgray">0</span>';
    }

}
