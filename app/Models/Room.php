<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['descr', 'eventversion_id', 'order_by'];

    public function filecontenttypes()
    {
        return $this->belongsToMany(Filecontenttype::class);
    }

    public function instrumentations()
    {
        return $this->belongsToMany(Instrumentation::class);
    }
}
