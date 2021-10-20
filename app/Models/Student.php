<?php

namespace App\Models;

use App\Traits\SenioryearTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory, SenioryearTrait;

    public function getCurrentSchoolAttribute()
    {
        foreach($this->person->user->schools AS $school){

            if($school->grades &&
                in_array($this->getGradeAttribute(), $school->grades)){

                return $school;
            }
        }

        return new School;
    }

    public function getCurrentTeacherAttribute()
    {
        if($this->teachers->count() === 1){ return $this->teachers->first(); }

        foreach($this->teachers AS $teacher){

            if($this->getCurrentSchoolAttribute()->teachers->contains($teacher)){

                return $teacher;
            }
        }
    }

    public function getGradeAttribute()
    {
        $sr_year = $this->senioryear();

        //early exit
        if($this->classof < $sr_year){ return 'alum';}

        return (12 - ($this->classof - $sr_year));
    }

    public function getEmailPersonalAttribute()
    {
        return Nonsubscriberemail::where('user_id',$this->user_id)
                ->where('emailtype_id', Emailtype::where('descr', 'email_student_personal')->first()->id)
                ->first()
            ?? new Nonsubscriberemail;
    }

    public function getEmailSchoolAttribute()
    {
        return Nonsubscriberemail::where('user_id',$this->user_id)
                ->where('emailtype_id', Emailtype::where('descr', 'email_student_school')->first()->id)
                ->first()
            ?? new Nonsubscriberemail;
    }

    public function getEmailsCsvAttribute()
    {
        $emails = [];

        if($this->getEmailPersonalAttribute()->id){
            $emails[] = $this->getEmailPersonalAttribute()->email;
        }

        if($this->getEmailSchoolAttribute()->id){
            $emails[] = $this->getEmailSchoolAttribute()->email;
        }

        return implode(', ',$emails);
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'user_id', 'user_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'user_id', 'user_id');
    }

    public function registrants()
    {
        return $this->hasMany(Registrant::class, 'user_id', 'user_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'student_teacher','student_user_id', 'teacher_user_id')
            ->withPivot('studenttype_id')
            ->withTimestamps()
            ->orderBy('updated_at','desc');
    }
}
