<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public function currentEventversion()
    {
        $eventversion_id = Userconfig::getValue('eventversion', auth()->id());

        return ($eventversion_id) ? Eventversion::find($eventversion_id) : new Eventversion;
    }

    public function eventversions()
    {
        return $this->hasMany(Eventversion::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


}
