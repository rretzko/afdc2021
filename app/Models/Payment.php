<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public function sumByEventversion(Eventversion $eventversion)
    {
        return $this->where('eventversion_id', $eventversion->id)->sum('amount');
    }

    public function sumBySchool(Eventversion $eventversion, School $school)
    {
        return $this->where('eventversion_id', $eventversion->id)
            ->where('school_id', $school->id)
            ->sum('amount');
    }
}
