<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'user_id';
    protected $with = ['person:user_id,last,first'];

    /**
     * Derive teacher's current school from teacher's newest student's current school
     * @todo this can be vastly improved...
     * @return mixed
     */
    public function currentSchool()
    {
        //derive from teacher's schools
        if($this->schools->count() === 2) {
            foreach ($this->schools as $school) {

                if(! strstr($school->name, 'Studio')){

                    return $school;
                }
            }
        }

        //otherwise derive school from last student
        $last = new Student;
        foreach($this->students AS $student){

            if($student->user_id > $last->user_id){ $last = $student;}
        }

        //conditional added as workaround where Matthew Lee, Nicole Hodge and Jeffrey Woodworth were displaying blanks
        return (strlen($last->currentSchool->name) ? $last->currentSchool : $this->schools->first());
    }

    public function getSchoolsAttribute()
    {
        return $this->person->user->schools;
    }

    public function getIsAcknowledgedAttribute() : bool
    {
        return (bool)Obligation::where('eventversion_id', Userconfig::getValue('eventversion',auth()->id()))
            ->where('user_id', $this->user_id)
            ->where('acknowledgment',1)
            ->first();
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'user_id', 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class,'student_teacher','teacher_user_id', 'student_user_id')
            ->withPivot('studenttype_id')
            ->withTimestamps()
            ->orderBy('updated_at','desc');
    }
}
