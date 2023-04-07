<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolVerified extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id','school_id','verified'];
}
