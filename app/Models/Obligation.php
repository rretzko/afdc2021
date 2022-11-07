<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obligation extends Model
{
    use HasFactory;

    public function person()
    {
        return $this->belongsTo(Person::class,'user_id','user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class,'user_id','user_id');
    }
}
