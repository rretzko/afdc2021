<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventversiondate extends Model
{
    use HasFactory;

    protected $fillable = ['datetype_id', 'dt','eventversion_id'];
}
