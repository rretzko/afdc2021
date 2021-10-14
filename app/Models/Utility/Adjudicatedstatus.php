<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjudicatedstatus extends Model
{
    use HasFactory;

    private $eventversion;
    private $countscores;
    private $registrant;
    private $registrantscores;
    private $scores;
    private $status;

    protected $fillable = ['registrant'];

    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->registrant = $attributes['registrant'];
        $this->eventversion = $this->registrant->eventversion;

        $this->status = 'unauditioned';

        $this->init();
    }

    public function status()
    {
        if($this->unauditioned()){
            return 'unauditioned';
        }elseif($this->tolerance()){
            return 'tolerance';
        }elseif($this->partial()) {
            return 'partial';
        }elseif($this->excess()){
            return 'excess';
        }elseif($this->completed()) {
            return 'completed';
        }else{
            return 'error';
        }
    }
    /** END OF PUBLIC PROPERTIES *************************************************/

    private function init()
    {
        //Count of  component scores possible for $this->eventversion
        $this->countscores = (\App\Models\Scoringcomponent::where('eventversion_id', $this->eventversion->id)->count() *
            $this->eventversion->eventversionconfig->judge_count);

        //Object to access all scores for $this->registrant
        $this->scores = new \App\Models\Utility\Registrantscores(['registrant' => $this->registrant]);

        //Count of component scores registered for $this->registrant
        $this->registrantscores = $this->scores->componentscores()->count();

        //Adjudicators registered for $this->registrant
        $this->adjudicators = $this->registrant->adjudicators();
    }

    private function completed()
    {
        return $this->registrantscores === $this->countscores;
    }

    private function excess()
    {
        return $this->registrantscores > $this->countscores;
    }

    private function partial()
    {
        return $this->registrantscores < $this->countscores;
    }

    /**
     * Return true if OUT of tolerance
     * @todo TEST FOR ADJUDICATOR ASSIGNED TO TWO ROOMS with SAME and DIFFERENT REGISTRANT POOLS
     * @return bool
     */
    private function tolerance()
    {return false;
        //container for total scores
        $scores = [];

        //iterate through each of the room's adjudicators to determine their total ROOM score FOR $this->registrant
        foreach($this->adjudicators AS $adjudicator){

            $scores[] = \App\Models\Score::where('registrant_id', $this->registrant->id)
                ->where('user_id', $adjudicator->user_id)
                ->sum('score');
        }

        //Return true if OUT of tolerance
        return ((max($scores) - min($scores)) > $this->room->tolerance);
    }

    private function unauditioned()
    {
        return (! $this->registrantscores);
    }
}
