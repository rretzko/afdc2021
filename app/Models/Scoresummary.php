<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scoresummary extends Model
{
    use HasFactory;

    protected $fillable = ['result'];
    public function registrantScore(\App\Models\Registrant $registrant)
    {
        return $this->where('registrant_id', $registrant->id)
            ->value('score_total');
    }
}
