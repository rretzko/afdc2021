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
}
