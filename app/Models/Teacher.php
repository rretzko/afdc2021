<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'user_id';

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
