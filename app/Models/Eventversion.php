<?php

namespace App\Models;

use App\Models\Eventversionconfig;
use App\Traits\SenioryearTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Eventversion extends Model
{
    use HasFactory, SenioryearTrait;

    /**
     * @return simple array of classofs for $this->eventversionconfig->grades
     */
    public function classofs()
    {
        return array_map(function($grade){
                return ($this->senioryear() + (12 - $grade));
            }, explode(',',$this->eventversionconfig->grades));
    }

    public function countInvitations()
    {
        return 15;
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventversionconfig()
    {
        return $this->hasOne(Eventversionconfig::class);
    }

    public function eventversiondates()
    {
        return $this->hasMany(Eventversiondate::class);
    }

    public function filecontenttypes()
    {
        return $this->belongsToMany(Filecontenttype::class)
            ->withPivot(['title','order_by'])
            ->orderByPivot('order_by');
    }

    public function filecontenttypeScoringcomponents($filecontenttype)
    {
        return Scoringcomponent::where('filecontenttype_id', $filecontenttype->id)
            ->where('eventversion_id', $this->id)
            ->orderBy('order_by')
            ->get();
    }

    /**
     * Scope a query to only include schools in Eventversion grades.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function schools()
    {
        //ex.[9,10,11,12]
        $grades = explode(',', $this->eventversionconfig->grades);
        $memberships = Membership::where('organization_id', $this->event->organization->id)->get();

        $raw = DB::table('gradetype_school_user')
            ->whereIn('gradetype_id', $grades)
            ->distinct()
            ->get()
            ->pluck('school_id')
            ->toArray();

        $schoolswitheventversiongrades = array_unique($raw);

        $schools = [];

        foreach($memberships AS $membership){

            foreach($membership->user->schools AS $school){

                if(in_array($school->id, $schoolswitheventversiongrades)){

                    $schools[$school->id] = $school;
                }
            }
        }

        $collection = collect($schools);

        return ($collection->sortBy('name'));
    }

    public function schoolsByCounties(array $counties)
    {
        $schools = collect();

        foreach($this->schools() AS $school){

            if(in_array($school->county_id, $counties)){

                $schools->push($school);
            }
        };

        return $schools;
    }

    public function countInstrumentationForSchool(School $school, Instrumentation $instrumentation)
    {
        return rand(0,90);
    }

    public function eventensembles()
    {
        return $this->hasMany(Eventensemble::class);
    }


    public function getParticipatingSchoolsAttribute()
    {
        $schoolids = DB::select(DB::raw("
            SELECT DISTINCT registrants.school_id, schools.name
            FROM registrants, schools
            WHERE registrants.eventversion_id= :eventversion_id
            AND registrants.registranttype_id=16
            AND registrants.school_id=schools.id
            ORDER BY schools.name
        "), ['eventversion_id' => $this->id]
        );

        $c = collect();

        foreach($schoolids AS $stdobj){

            $c->push(School::find($stdobj->school_id));
        }

        return $c;
    }

    /**
     * A teacher is participating if the teacher has confirmed a signature
     * @return \Illuminate\Support\Collection
     */
    public function getParticipatingTeachersAttribute()
    {
        $min = ($this->id * 10000);
        $max = ($min + 10000);

        return Teacher::whereIn('user_id',Signature::where('registrant_id', '>=', $min)
            ->where('registrant_id', '<', $max)
            ->distinct()
            ->pluck('confirmed_by'))
            ->get()
            ->sortBy(['person.last', 'person.first']);
    }

    //SJCDA defines registered students as have two esignatures
    //use the Obligations table to determine those teachers who (at least) signed up to participate
    public function getParticipatingTeachersEsignatureAttribute()
    {
        $registrants = Registrant::whereIn('id',
            Eapplication::where('eventversion_id', $this->id)
            ->where('signatureguardian', 1)
            ->where('signaturestudent', 1)
            ->get('registrant_id')
            ->toArray())
            ->get();

        //initialize empty collection
        $teachers = collect();

        foreach($registrants AS $registrant) {

            $teacher = Student::find($registrant->user_id)->currentTeacher;

            if($teacher){

                $teachers->push($teacher);
            }
        }

        return $teachers->unique('user_id')->sortBy('person.last');
    }

    /**
     * Instrumentation is assigned to eventensembletype
     *
     * This method returns the instrumentation for the FIRST ensemble
     * found for $this->event
     *
     * @return mixed
     */
    public function instrumentations()
    {
        return $this->event->eventensembles->first()->instrumentations();
    }

    public function registrantsForSchool(School $school)
    {
        $registrants = Registrant::with('student','student.person')
            ->where('eventversion_id', $this->id)
            ->where('school_id', $school->id)
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->get();

        return $registrants->sortBy('student.person.last');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function scoringcomponents()
    {
        return $this->hasMany(Scoringcomponent::class)
            ->orderBy('order_by');
    }

}
