<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public function person()
    {
        return $this->belongsTo(Person::class, 'user_id', 'user_id');
    }

    public function registrants()
    {
        return $this->hasMany(Registrant::class, 'user_id', 'user_id');
    }
}
