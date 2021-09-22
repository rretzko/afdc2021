<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userconfig extends Model
{
    use HasFactory;

    protected $fillable=['user_id', 'descr', 'value'];

    static public function getValue($descr, $user_id)
    {
        $value = self::where('user_id', $user_id)
            ->where('descr', $descr)
            ->first()->value ?? false;

        //early exit
        if($value){ return $value;};

        return self::setValue($descr, $user_id);
    }

    static public function updateValue($descr, $user_id, $value)
    {
        self::updateOrCreate(
            [
                'user_id' => $user_id,
                'descr' => $descr,
            ],
            [
                'value' => $value,
            ]
        );
    }

/** END OF PUBLIC FUNCTIONS **************************************************/

    static private function defaultFactory($descr, $user_id)
    {
        $method = 'find'.ucwords($descr);

        return self::$method($user_id);
    }

    static private function findEventversion($user_id)
    {
        $event = Event::find(self::getValue('event', $user_id));

        return $event->eventversions->last()->id;
    }

    static private function findEvent($user_id)
    {
        $organization = Organization::find(self::getValue('organization', $user_id));

        return $organization->events->last()->id;
    }

    static private function findOrganization($user_id)
    {
        $membership = new Membership;

        $usermemberships = $membership->hasMember($user_id);

        return $usermemberships->first()->id;
    }

    static private function setValue($descr, $user_id)
    {
        self::insert([
            'user_id' => $user_id,
            'descr' => $descr,
            'value' => self::defaultFactory($descr, $user_id),
            ]);

        //recursive call after setting default value
        return self::getValue($descr, $user_id);
    }
}
