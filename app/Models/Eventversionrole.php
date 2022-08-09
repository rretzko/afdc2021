<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventversionrole extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id','membership_id','roletype_id','user_id'];
}
