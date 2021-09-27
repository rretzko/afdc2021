<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public function registrant()
    {
        return $this->belongsTo(Registrant::class, 'id', 'registrant_id');
    }
}
