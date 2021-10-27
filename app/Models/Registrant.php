<?php

namespace App\Models;

use App\Models\Utility\Fileviewport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrant extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id', 'id', 'programname', 'registranttype_id', 'school_id', 'user_id'];

    public function adjudicatedStatus()
    {
        $status = new \App\Models\Utility\Adjudicatedstatus(['registrant' => $this]);

        return $status->status();
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

        $card = $this->student->person->fullnameAlpha().$crlf;

        $card .= $this->student->currentSchool->shortName.$crlf;

        $card .= 'Score count: '.$scores->count().$crlf;
        //$card .= $this->student->currentTeacher;

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
        $viewport = new Fileviewport($this,$filecontenttype);

        return $viewport->viewport();
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

    public function school()
    {
        return School::find($this->school_id);
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
            'error' => 'rgba(0,0,0,.1)',
            'partial' => 'rgba(240,255,0,.1)',//'bg-yellow-100',
            'tolerance' => 'rgba(255,0,0,.1)',//'bg-red-100',
            'unauditioned' => '',//bg-white
        ];

        return $colors[$this->adjudicatedStatus()];
    }

}
