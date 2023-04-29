<?php

namespace App\Models;

use App\Models\Utility\Fileviewport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\RegistrantRoomsTrait;

class Registrant extends Model
{
    use HasFactory,RegistrantRoomsTrait;

    protected $fillable = ['eventversion_id', 'id', 'programname', 'registranttype_id', 'school_id', 'user_id'];

    public function adjudicatedStatus()
    {
        $status = new \App\Models\Utility\Adjudicatedstatus(['registrant' => $this]);

        return $status->status();
    }

    /**
     * Return string for tooltip
     * @param Room $room
     * @return string
     */
    public function adjudicatorProgress(Room $room): string
    {
        $str = '';

        foreach($room->adjudicators->sortBy('adjudicatorname') AS $adjudicator){
            $str .= $adjudicator->adjudicatorname.': '.$this->scorecountByAdjudicator($adjudicator).' ('.$this->scoreSumByAdjudicator($adjudicator).') &#13;';
        }

        //$str = 'Adjudicator 1: 9 &#13; Adjudicator 2: 6 &#13; Adjudicator 3: 0';

        return $str;
    }

    public function adjudicators()
    {
        return Adjudicator::whereIn('user_id', Score::where('registrant_id', $this->id)
            ->pluck('user_id')
            ->toArray())
            ->get();
    }

    public function application()
    {
        return $this->hasOne(Application::class,'id', 'registrant_id');
    }

    public function auditionDetails()
    {
        $scores = Score::where('registrant_id', $this->id)->get();
        $crlf = '&#13;';

        //student name and instrumentation
        $card = $this->student->person->fullnameAlpha().' ('.strtoupper($this->instrumentations->first()->abbr).')'.$crlf;

        //school name
        $card .= ($this->student->currentSchool)
            ? '@ '.$this->student->currentSchool->shortName.$crlf
            : 'No school found; check logs';

        //teacher name
        if(! $this->student->currentTeacher){ dd($this->id . ': ' . $this->user_id . ': ' . $this->programname);}
        $card .= 'w/'
            . $this->student->currentTeacher->person ? $this->student->currentTeacher->person->alphaName . $crlf : 'Teacher missing ' . $crlf;

        //score count
        $card .= 'Score count: '.$scores->count().$crlf;
/*
        //rooms
        foreach($this->registrantRooms($this) AS $room){

            $card .= $room->descr.' Room'.$crlf;

            foreach($room->adjudicators AS $adjudicator){

                $card .= '- '.$adjudicator->adjudicatorName.' ('.$this->scoreSumByAdjudicator($adjudicator).')'.$crlf;
            }
        }
*/
        return $card;
    }

    public function eventversion()
    {
        return $this->belongsTo(Eventversion::class);
    }

    public function fileuploads()
    {
        $collect = collect();
        $eventversion = Eventversion::find($this->eventversion_id);

        foreach($eventversion->filecontenttypes AS $filecontenttype){

            $collect->push(Fileupload::where('registrant_id', $this->id)
                ->where('filecontenttype_id', $filecontenttype->id)
                ->first());
        }

        return $collect;
    }

    public function fileUploaded(Filecontenttype $filecontenttype)
    {
        return Fileupload::where('registrant_id', $this->id)
            ->where('filecontenttype_id', $filecontenttype->id)
            ->first();
    }

    public function fileUploadedAndApproved(Filecontenttype $filecontenttype)
    {
        return Fileupload::where('registrant_id', $this->id)
            ->where('filecontenttype_id', $filecontenttype->id)
            ->whereNotNull('approved')
            ->first();
    }

    /**
     * Return the embed code for the requested videotype
     *
     * NOTE: self::hasVideoType($videotype) should be run BEFORE this function.
     *
     * @param Videotype $videotype
     * @return string
     */
    public function fileviewport(Filecontenttype $filecontenttype)
    {
        if(! $this->fileUploaded($filecontenttype)){

            return 'No file found';
        }

        if(! $this->fileUploadedAndApproved($filecontenttype)){

            return 'Unapproved file found';
        }

        $viewport = new Fileviewport($this,$filecontenttype);

        return $viewport->viewport();
    }

    public function getCurrentTeacherAttribute()
    {
        return $this->student->getCurrentTeacherAttribute();
    }

    /**
     * Provide a string to sort by full name alpha
     * @return string
     */
    public function getFullnameAlphaAttribute()
    {
        return $this->student ? $this->student->person->fullnameAlpha() : 'No Name';
    }

    public function getArmytimeAttribute() : string
    {
        return Timeslot::where('school_id', $this->school_id)
            ->where('eventversion_id', $this->eventversion_id)
            ->first()->armytime ?? 'None found';
    }

    public function getSchoolnameAttribute() : string
    {
        return $this->school()->name;
    }

    public function getSchoolShortnameAttribute() : string
    {
        return $this->school()->shortname;
    }

    public function getTimeslotAttribute() : string
    {
        return Timeslot::where('school_id', $this->school_id)
                ->where('eventversion_id', $this->eventversion_id)
                ->first()->timeslot ?? 'None found';
    }

    public function grandtotal()
    {
        $scoresummary =Scoresummary::where('registrant_id', $this->id)->first();

        return $scoresummary ? $scoresummary->score_total : 0;
    }

    public function instrumentations()
    {
        return $this->belongsToMany(Instrumentation::class);
    }

    public function roomStatusColor(Room $room)
    {
        //calc scoring components per adjudicator
        $scoringcomponents = 0;
        foreach ($room->filecontenttypes as $filecontenttype) {

            $scoringcomponents += DB::table('scoringcomponents')
                ->where('eventversion_id', $this->eventversion_id)
                ->where('filecontenttype_id', $filecontenttype->id)
                ->count('id');
        }

        //total components available for registrant in room
        $totalcomponents = ($scoringcomponents * $room->adjudicators->count());

        $scoringcomponentids = $room->scoringcomponents()->pluck('id')->toArray();

        //count of adjudicated components from scores table
        $adjudicatedcomponents = DB::table('scores')
            ->where('registrant_id', $this->id)
            ->whereIn('scoringcomponent_id', $scoringcomponentids)
            ->count('id');

        //simple sum of scores per adjudicator (for use in tolerance testing)
        $adjudicatorscores = [];
        foreach($room->adjudicators AS $adjudicator)
        {
            $adjudicatorscores[] = $this->scorecountByAdjudicator($adjudicator);
        }

        //colors
        $unauditioned = 'white;'; //white
        $partial = 'rgba(240,255,0,0.3);'; //yellow
        $completed = 'rgba(0,255,0,0.1);'; //green
        $tolerance = 'rgba(255,0,0,0.1);'; //red
        $excess = 'rgba(0,0,255,0.3);'; //darkblue
        $error = 'rgba(0,0,0,0.2);'; //gray

        if (! $adjudicatedcomponents) {
            return $unauditioned;
        } elseif ($adjudicatedcomponents && ($adjudicatedcomponents < $totalcomponents)) {
            return $partial;
        } elseif ($adjudicatedcomponents === $totalcomponents) {
            return $completed;
        } elseif ($adjudicatedcomponents && ($adjudicatedcomponents === $totalcomponents) && ((max($adjudicatorscores) - min($adjudicatorscores) > $room->tolerance))) {
            return $tolerance;
        } elseif ($adjudicatedcomponents > $totalcomponents) {
            return $excess;
        }else{
            return $error;
        }

    }

    public function school()
    {
        return School::find($this->school_id);
    }

    public function scorecountByAdjudicator(Adjudicator $adjudicator)
    {
        return DB::table('scores')
            ->where('registrant_id', $this->id)
            ->where('user_id', $adjudicator->user_id)
            ->count('id');
    }

    public function scoreSumByAdjudicator(Adjudicator $adjudicator)
    {
        return DB::table('scores')
            ->where('registrant_id', $this->id)
            ->where('user_id', $adjudicator->user_id)
            ->sum('score');
    }

    public function scoresummary()
    {
        return Scoresummary::where('registrant_id', $this->id)->first() ?? new Scoresummary;
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }

    public function tabroomStatusBackgroundColor()
    {
        $colors = [
            'completed' => 'rgba(0,255,0,.1)',//'bg-green-100',
            'excess' => 'rgba(44,130,201,.1)', //bg-blue-100,
            'error' => 'rgba(0,0,0,.2)',
            'partial' => 'rgba(240,255,0,.3)',//'bg-yellow-100',
            'tolerance' => 'rgba(255,0,0,.1)',//'bg-red-100',
            'unauditioned' => '',//bg-white
        ];

        return $colors[$this->adjudicatedStatus()];
    }

}
