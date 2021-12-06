<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Massmailingtype extends Model
{
    use HasFactory;

    protected $fillable = ['descr'];

    const ABSENTSTUDENT = 1;
    const EARLYEXIT = 2;
    const LATEARRIVAL = 3;
    const CONCERT = 4;
    const REHEARSAL = 5;
    const REMINDER = 6;
}
