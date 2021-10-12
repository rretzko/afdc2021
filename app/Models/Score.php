<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id', 'scoringcomponent_id', 'proxy_id', 'registrant_id','score', 'user_id'];

}
