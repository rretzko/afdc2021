<?php

namespace App\Models\Utility;

use App\Models\Eventversion;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    static private $eventversion;

    public function __construct(Eventversion $eventversion)
    {
        self::$eventversion = $eventversion;
    }

    static public function invitedTeachers(): array
    {
        $membership = new Membership;

        return $membership->where('organization_id', self::$eventversion->event->organization->id)->get()->toArray();
    }
}
