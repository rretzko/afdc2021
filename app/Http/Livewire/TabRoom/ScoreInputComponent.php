<?php

namespace App\Http\Livewire\TabRoom;

use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Room;
use App\Models\Score;
use App\Models\Scoresummary;
use App\Models\Scoringcomponent;
use App\Models\Userconfig;
use Illuminate\Support\Collection;
use Livewire\Component;

class ScoreInputComponent extends Component
{
    public $adjudicatorid = 0;
    public $adjudicators = null;
    public $eventversion = null;
    public $size = 'l';
    public $registrantdetail = '';
    public $registrantid = "";
    public $registrantiderror = '';
    public $room = null;
    public $roomid = 0;
    public $rooms = null;
    public $scoresupdated = '';
    public $scoretotal = 0;
    public $scoringcomponents = null;
    public $scoringerrors = '';
    public $scores = [];

    public function mount()
    {
        $this->eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $this->rooms = $this->eventversion->rooms;
        $this->room = $this->rooms->first();
        $this->roomid = $this->room->id;

        $this->adjudicators = $this->rooms->first()->adjudicators;
        $this->adjudicatorid = $this->adjudicators->first()->user_id;

        $this->scoringcomponents = $this->room->scoringcomponents();
    }
    public function render()
    {
        return view('livewire.tab-room.score-input-component',
        [

        ]);
    }

    public function updatedRegistrantid($value)
    {
        if(strlen($value) === 6) {

            $registrant = Registrant::find($value);

            if (is_null($registrant)) {

                $this->registrantiderror = 'Registrant id: ' . $value . ' not found!';

            }elseif(! $this->matchedInstrumentation($registrant)) {

                $this->registrantiderror = 'Mismatched registrant voice part: ' . strtoupper($registrant->instrumentations->first()->abbr);

            }else{//happy path

                $this->registrantdetail = $registrant->programname.' ('.strtoupper($registrant->instrumentations->first()->abbr).')';
                $this->reset('registrantiderror','scores', 'scoretotal');
                $this->populateScores($registrant);
            }
        }else{

            $this->reset('registrantdetail','registrantiderror','scores','scoretotal');
        }
    }

    public function updatedRoomid($value)
    {
        $this->room = Room::find($value);
        $this->adjudicators = $this->room->adjudicators;
        $this->adjudicatorid = $this->adjudicators->first()->user_id;

        $this->scoringcomponents = $this->room->scoringcomponents();

        $this->reset('registrantdetail','registrantid','registrantiderror','scores', 'scoretotal');
    }

    public function updatedScores()
    {
        $this->reset('scoresupdated', 'scoringerrors');

        foreach($this->scores AS $scoringcomponentid => $score){

            if(empty($score) || (! is_numeric($score))){

                $score = 0;
            }

            $sc = Scoringcomponent::find($scoringcomponentid);

            $this->checkScoringRange($sc, (int)$score);

            $this->updateScore($scoringcomponentid, $score);

            $this->calcAdjudicatorScoreTotal();
        }

        $this->updateScoresummary();
    }

    /** END OF PUBLIC FUNCTIONS **********************************************/

    private function calcAdjudicatorScoreTotal(): void
    {
        $this->scoretotal = 0;

        if(! strlen($this->scoringerrors)){

            $scores = Score::where('registrant_id', $this->registrantid)
                ->where('user_id', $this->adjudicatorid)
                ->get();

            $this->scoretotal = $scores->sum('score');
        }
    }
    /**
     * check scores are within scoring range
     * @param Scoringcomponent $sc
     * @param int $score
     * @return void
     */
    private function checkScoringRange(Scoringcomponent $sc, int $score): void
    {
        $minmax = [$sc->bestscore, $sc->worstscore];

        if(($score < min($minmax)) || ($score > max($minmax))){

            $this->scoringerrors = $score.' is outside of scoring range ('.min($minmax).' - '.max($minmax).')';
        }
    }

    private function matchedInstrumentation(Registrant $registrant): bool
    {
        $registrant_instrumentation = $registrant->instrumentations->first();
        $room_instrumentations = $this->room->instrumentations;

        return $room_instrumentations->contains($registrant_instrumentation);
    }

    private function populateScores(Registrant $registrant)
    {
        $s = Score::where('registrant_id', $registrant->id)->where('user_id', $this->adjudicatorid)->get();

        foreach($s AS $score){

            $this->scores[$score->scoringcomponent_id] = $score->score;
        }

        $this->scoretotal = array_sum($this->scores);
    }

    /**
     * if no errors are reported, update Scores table
     * @param int $scoringcomponent_id
     * @param int $score
     * @return void
     */
    private function updateScore(int $scoringcomponent_id, int $score): void
    {
        if(! strlen($this->scoringerrors)){

            Score::updateOrCreate(
                [
                    'registrant_id' => $this->registrantid,
                    'eventversion_id' => $this->eventversion->id,
                    'user_id' => $this->adjudicatorid,
                    'scoringcomponent_id' => $scoringcomponent_id,
                ],
                [
                    'score' => $score,
                    'proxy_id' => $this->adjudicatorid,
                ]
            );
        }
    }

    /**
     * if no errors are reported, update Scoresummaries table
     * @return void
     */
    private function updateScoreSummary(): void
    {
        if(! strlen($this->scoringerrors)){

            $registrant = Registrant::find($this->registrantid);
            $scores = Score::where('registrant_id', $registrant->id)->get();

            $ss = Scoresummary::updateOrCreate(
                [
                    'eventversion_id' => $this->eventversion->id,
                    'registrant_id' => $registrant->id,
                    'instrumentation_id' => $registrant->instrumentations->first()->id,
                ],
                [
                    'score_total' => $scores->sum('score'),
                    'score_count' => $scores->count('score'),
                    'result' => 'pend',
                ]
            );

            if($ss->id){

                $this->scoresupdated = 'Scores updated at '.date('M d, Y g:i:s a');
            }
        }
    }
}
