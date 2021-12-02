<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Massmailingvar extends Model
{
    use HasFactory;

    protected $fillable = ['descr', 'massmailing_id', 'order_by', 'var'];
}
