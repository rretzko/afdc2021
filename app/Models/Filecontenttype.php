<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filecontenttype extends Model
{
    use HasFactory;

    public function scoringcomponents()
    {
        return $this->hasMany(Scoringcomponent::class);
    }
}
