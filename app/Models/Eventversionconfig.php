<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventversionconfig extends Model
{
    use HasFactory;

    public function eventversion()
    {
        return $this->belongsTo(Eventversion::class);
    }
}
