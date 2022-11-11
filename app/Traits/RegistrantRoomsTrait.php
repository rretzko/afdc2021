<?php

namespace App\Traits;

use App\Models\Registrant;
use App\Models\Room;

trait RegistrantRoomsTrait
{
    public function registrantRooms(Registrant $registrant)
    {
        $instrumentation_id = $registrant->instrumentations->first()->id;

        //get all rooms in $this->registrant->eventversion_id
        $allrooms = Room::with('instrumentations')
            ->where('eventversion_id',$registrant->eventversion_id)
            ->get();

        //filter $allrooms to $rooms containing $instrumentation_id
        $rooms = $allrooms->filter(function($room) use($instrumentation_id){

            foreach($room->instrumentations AS $instrumentation){

                if($instrumentation->id == $instrumentation_id){

                    return true;
                }
            }
        });

        return $rooms;
    }

}
