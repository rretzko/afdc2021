<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phonetype extends Model
{
    use HasFactory;

    const HOME = 3;
    const MOBILE = 1;
    const WORK = 2;
    const STUDENT_HOME = 5;
    const STUDENT_MOBILE = 4;

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }
}
