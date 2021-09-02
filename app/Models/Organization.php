<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Returns the user's current event or the most current event in the Organization's stack
     * or new Event if no events exist
     * @return mixed
     */
    public function currentEvent()
    {
        $event_id = Userconfig::getValue('event', auth()->id());

        return ($event_id) ? Event::find($event_id) : new Event;
    }

    public function memberships()
    {
       return $this->hasMany(Membership::class);
    }
}
