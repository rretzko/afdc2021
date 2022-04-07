<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class School extends Model
{
    use HasFactory;

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    /**
     * Workaround:
     * 1. find current eventversion registrant
     * 2. find most-recent student-teacher relationship
     * 3. use that user_id to return Teacher class
     * @return mixed
     */
    public function currentTeacher($eventversion)
    {
        $registrant = Registrant::where('school_id',$this->id)
            ->where('eventversion_id', $eventversion->id)
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->first();

        //early exit
        if(! $registrant){ return new Teacher(); }

        return $registrant->student->currentTeacher;
/*
        $teacher_user_id = DB::table('student_teacher')
            ->where('student_user_id', $registrant->user_id)
            ->orderByDesc('updated_at')
            ->pluck('teacher_user_id')
            ->toArray();

        return (count($teacher_user_id)) ? Teacher::find($teacher_user_id[0]) : new Teacher;
*/
    }

    public function applicantsCount(Eventversion $eventversion) : int
    {
        $min = ((($eventversion->id - 1) * 10000) + 9999);
        $max = (($eventversion->id + 1) * 10000);

        return DB::table('applications')
            ->join('registrants', function($join){
                $join->on('registrants.id','=','applications.registrant_id')
                    ->where('registrants.school_id', '=', $this->id);
            })
            ->where('registrant_id','>',$min)
            ->where('registrant_id','<',$max)
            ->distinct()
            ->count('registrant_id');
    }

    /**
     * Return array of all grades found for $this
     */
    public function getGradesAttribute() : array
    {
        return DB::table('gradetype_school_user')
            ->select('gradetype_id')
            ->where('school_id', $this->id)
            ->distinct()
            ->pluck('gradetype_id')
            ->toArray();
    }

    /**
     * @since 2020.05.28
     *
     * abbreviate common terms
     *
     * @return string
     */
    public function getShortNameAttribute() : string
    {
        $abbrs = [
            'Regional High School' => 'RHS',
            'High School' => 'HS',
            'International' => 'Int\'l',
            'School District' => 'SD',
            'Township' => 'Twp',
            'University' => 'U',
        ];

        $haystack = $this->name; //avoid repeated downstream calls
        $str = $haystack;   //initialize $str value

        //graceful fail
        if(! $haystack){ return 'No School Found';}

        foreach($abbrs AS $descr => $abbr){

            //no change has been made
            if($str === $haystack){
                if (strstr($haystack, $descr)) {

                    $str = str_replace($descr, $abbr, $haystack);
                }
            }else {//a change has occurred; use abbreviated value

                if (strstr($str, $descr)) {

                    $str = str_replace($descr, $abbr, $str);
                }
            }
        }

        return $str;
    }

    public function getTeachersAttribute()
    {
        return Teacher::with(['person' => function($query){
            $query->orderBy('last');
            }])
            ->whereIn('user_id', $this->users->modelKeys())
            ->get();

    }

    public function timeslot(Eventversion $eventversion)
    {
        return Timeslot::where('school_id', $this->id)
            ->where('eventversion_id', $eventversion->id)
            ->first()->timeslot ?? '';
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
