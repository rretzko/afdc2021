<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audiencetype extends Model
{
    use HasFactory;

    protected $fillable = ['descr'];

    const STUDENTS = 1;
    const PARENTS = 2;
    const TEACHERS = 3;
}
