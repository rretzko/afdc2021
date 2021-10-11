<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public function getCurrentSchoolAttribute()
    {
        foreach($this->person->user->schools AS $school){

            if($school->currentUserGrades &&
                in_array($this->getGradeAttribute(), $school->currentUserGrades)){

                return $school;
            }
        }

        return new School;
    }

    public function getCurrentTeacherAttribute()
    {
        //early exit
        if($this->teachers->count() === 1){ return $this->teachers->first(); }

        foreach($this->teachers AS $teacher){

            if($this->getCurrentSchoolAttribute()->teachers->contains($teacher)){

                return $teacher;
            }
        }
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
        return $this->belongsToMany(Teacher::class, 'user_id', 'user_id');
    }
}
