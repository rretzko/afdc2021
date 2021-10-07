<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjudicator extends Model
{
    use HasFactory;

    protected $fillable = ['adjudicatorstatustype_id', 'eventversion_id', 'room_id', 'user_id'];

    public function getAdjudicatornameAttribute()
    {
        return User::find($this->user_id)->person->fullnameAlpha();
    }


}
