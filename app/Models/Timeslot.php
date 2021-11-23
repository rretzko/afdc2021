<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id', 'school_id', 'armytime', 'timeslot'];

    /**
     * Timeslots between 8am and 6pm on $interval minutes
     * @param int $starttime
     * @param int $endtime
     * @param int $interval
     * @return array
     */
    public function buildTimeslots($interval) : array
    {
        $a = [];
        $starthour = 8;
        $startminute = 0;

        $endhour = 18;

        for($i=$starthour; $i<=$endhour; $i++){

            for($j=$startminute; $j<=59; $j=$j+$interval){

                $formatted = (strlen($j) === 2) ? $j : '0'.$j;

                $a[] = $i.':'.$formatted;
            }
        }

        return $a;
    }
}
