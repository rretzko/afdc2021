<?php

namespace App\Models\Utility;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RegistrantRoomsTrait;

class Adjudicatedstatus extends Model
{
    use HasFactory,RegistrantRoomsTrait;

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

    /**
     * Return string which serves as formatting class name (ex. class='tolerance')
     */
    public function status()
    {
        if($this->unauditioned()){
            return 'unauditioned';
        }elseif($this->tolerance()){ //i.e. out of tolerance
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
     * 1. Identify the rooms in which $this->registrant should have scores
     * 2. Identify the tolerance of each room
     * 3. Identify the adjudicators in each room
     * 4. Compare the score difference to the tolerance
     * 5. Any false = all false
     * @return bool
     */
    private function tolerance()
    {
        $out_of_tolerance = false; //default: registrant is within tolerance
        $instrumentation_id =$this->registrant->instrumentations->first()->id;

        //1. Identify the rooms in which $this->registrant should have scores
        foreach($this->registrantRooms($this->registrant) AS $room){

            //2. Identify the tolerance in each room
            $tolerance = $room->tolerance;

            //Test until an out_of_tolerance condition is found
            if(! $out_of_tolerance) {

                $scores = [];

                //3. Identify the adjudicator scores in the room
                foreach ($room->adjudicators as $adjudicator) {

                    $scores[] = $this->registrant->scoreSumByAdjudicator($adjudicator);
                }

                //4. Compare the score difference to the tolerance
                $out_of_tolerance = ((max($scores) - min($scores)) > $tolerance);
            }
        }

        return $out_of_tolerance;
    }

    /**
     * Return true if OUT of tolerance
     * @todo TEST FOR ADJUDICATOR ASSIGNED TO TWO ROOMS with SAME and DIFFERENT REGISTRANT POOLS
     * @return bool
     */
    /*private function tolerance_OLD()
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
    }*/

    private function unauditioned()
    {
        return (! $this->registrantscores);
    }
}
