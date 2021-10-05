<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adminreview extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id', 'registrant_id', 'reviewed', 'user_id'];
}
