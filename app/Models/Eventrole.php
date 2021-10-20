<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventrole extends Model
{
    use HasFactory;

    public function event()
    {
        return Event::find($this->event_id);
    }
}
