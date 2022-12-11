<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id', 'scoringcomponent_id', 'proxy_id', 'registrant_id','score', 'user_id'];

    private $eventversion;

    public function registrantScoringcomponentScore(\App\Models\Registrant $registrant, \App\Models\Scoringcomponent $scoringcomponent, $judge_index)
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return $this->where('registrant_id', $registrant->id)
            ->where('scoringcomponent_id', $scoringcomponent->id)
            ->where('eventversion_id', $eventversion->id)
            ->value('score');
    }

    /**
     * Return a score (0 if none found) based on:
     *  - Registrant
     *  - Filecontenttype
     *  - Instrumentation
     *  - Room
     *  - Scoringcomponent
     *
     * @todo Find a much better way to do this.
     * @todo Possibility: Use adjudicator_id with rank in place of user_id
     *
     * @param Registrant $registrant
     * @param Scoringcomponent $scoringcomponent
     * @param int $rank
     * @return int
     */
    public function registrantScoringcomponentScoreByAdjudicatorRank(Registrant $registrant, Scoringcomponent $scoringcomponent, int $rank)
    {
        $filecontenttype_id = $scoringcomponent->filecontenttype_id;
        $instrumentation_id = $registrant->instrumentations()->first()->id;

        //all rooms in eventversion
        $rooms = Room::where('eventversion_id', $registrant->eventversion_id)->get();
;
        //filter all rooms by instrumentation
        $roomsinstrumentation = $rooms->filter(function($roomi) use($instrumentation_id){
            return $roomi->instrumentations->contains($instrumentation_id);
        });

        //filter instrumentation by filecontenttype
        $roomsfilecontenttypes = $roomsinstrumentation->filter(function($roomf) use($filecontenttype_id){
            return $roomf->filecontenttypes->contains($filecontenttype_id);
        });

        //expectation is that there is ONLY ONE ROOM
        $room_id = $roomsfilecontenttypes->first()->id;

        //isolate specific adjudicator user_id from room_id
        $user_id = Adjudicator::where('rank', $rank)
            ->where('eventversion_id', $registrant->eventversion_id)
            ->where('room_id', $room_id)
            ->value('user_id');

        return $this::where('registrant_id', $registrant->id)
            ->where('scoringcomponent_id', $scoringcomponent->id)
            ->where('user_id', $user_id)
            ->value('score') ?? 0;
    }

    public function registrantScores(\App\Models\Registrant $registrant)
    {
        $this->eventversion = Eventversion::find($registrant->eventversion_id);
       //dd(Score::where('registrant_id', $registrant->id)->get());
        $scoringcomponents = Scoringcomponent::where('eventversion_id', $this->eventversion->id)->orderBy('order_by')-> get();

        $rooms = $this->filterRooms($this->eventversion, $registrant);

        $filecontenttypes = $this->eventversion->filecontenttypes;

        $scores = [];

        if($rooms->count()) {
            //for each judge
            for ($i = 0; $i < $this->eventversion->eventversionconfig->judge_count; $i++) { //0,1,2,3

                //for each file content type
                foreach ($filecontenttypes as $key => $filecontenttype) { //scales, solo, quartet

                    $room = $rooms->filter(function ($room) use ($filecontenttype) {
                        return $room->filecontenttypes->contains($filecontenttype);
                    });

                    $adjudicators = $room->first()->adjudicators;

                    //for each scoring component
                    foreach ($filecontenttype->scoringcomponents->where('eventversion_id', $this->eventversion->id) as $scoringcomponent) {

                        //error avoidance if full compliment of adjudicators is not available
                        if(isset($adjudicators[$i])) {

                            $scores = $this->where('registrant_id', $registrant->id)
                                ->select('score', 'scoringcomponent_id')
                                ->orderBy('user_id')
                                ->orderBy('scoringcomponent_id')
                                ->get()
                                ->toArray();

                            /*
                            $scores[] = $this->where('registrant_id', $registrant->id)
                                ->where('scoringcomponent_id', $scoringcomponent->id)
                                ->where('eventversion_id', $eventversion->id)
                                ->where('user_id', $adjudicators[$i]->user_id)
                                ->value('score');
                            */

                        }else{

                            $scores[] = 0;
                        }
                    }
                }
            }
        }

        return $this->mapScores($scores);
    }

    private function filterRooms($eventversion, $registrant)
    {
        $instrumentation = $registrant->instrumentations->first();

        $allrooms = Room::where('eventversion_id', $eventversion->id)->get();

        $rooms = $allrooms->filter(function($room) use($instrumentation) {
            return $room->instrumentations->contains($instrumentation);
        });

        return $rooms->sortBy('order_by');
    }

    /**
     * FOR NJMEA All-State Chorus reporting,
     * Scores are reported as-if one judge adjudicates all filecontenttypes for each registrant
     * In fact, three judges hear one filecontenttype (scales), and then
     * another three judges hear the second filecontenttype (solo), and then
     * another three judges hear the third filecontenttype (quintet)
     *
     * Each #1 judge for each filecontenttype is combined into a figurative Judge #1
     * Each #2 judge for each filecontenttype is combined into a figurative Judge #2
     * Each #3 judge for each filecontenttype is combined into a figurative Judge #3
     *
     * @param $eventversion
     */
    private function organizeAdjudicators($eventversion, $registrant)
    {
       $filecontenttypes = $eventversion->filecontenttypes;
       $allrooms = Room::with('adjudicators', 'instrumentations')
           ->where('eventversion_id', $eventversion->id)
           ->get();
       $rooms = collect();

       foreach($allrooms AS $room){

           if($room->instrumentations->contains($registrant->instrumentations->first())) {

               $rooms->push($room);
           }
       }

       //dd($rooms->sortBy('order_by'));
    }

    public function mapScores($scores)
    {
        //NJ All-State Chorus; 2022
        foreach($scores AS $score){

            $scoringcomponents[$score['scoringcomponent_id']][] = $score['score'];
        }

        if($scores) {
            switch ($this->eventversion->event->id) {
                case 1: //CJMEA
                    $scores =
                        [
                            $scoringcomponents[75][0], //scales quality
                            $scoringcomponents[76][0], //scales diatonic intonation
                            $scoringcomponents[77][0], //scales chromatic intonation
                            $scoringcomponents[78][0], //solo quality
                            $scoringcomponents[79][0], //solo intonation
                            $scoringcomponents[80][0], //solo musicanship
                            $scoringcomponents[81][0], //swan quality
                            $scoringcomponents[82][0], //swan intonation
                            $scoringcomponents[83][0], //swan musicianship

                            $scoringcomponents[75][1],
                            $scoringcomponents[76][1],
                            $scoringcomponents[77][1],
                            $scoringcomponents[78][1],
                            $scoringcomponents[79][1],
                            $scoringcomponents[80][1],
                            $scoringcomponents[81][1],
                            $scoringcomponents[82][1],
                            $scoringcomponents[83][1],
                        ];
                    break;
                case 19: //All-Shore

                    if (isset($scoringcomponents)) {
                        $scores = [
                            //All-Shore 2022-23
                            $scoringcomponents[64][0], //low scale
                            $scoringcomponents[65][0], //high scale
                            $scoringcomponents[66][0], //chromatic scale
                            $scoringcomponents[67][0], //arpeggio
                            $scoringcomponents[69][0], //quartet
                            $scoringcomponents[68][0], //solo

                            $scoringcomponents[64][1],
                            $scoringcomponents[65][1],
                            $scoringcomponents[66][1],
                            $scoringcomponents[67][1],
                            $scoringcomponents[69][1],
                            $scoringcomponents[68][1],

                            $scoringcomponents[64][2],
                            $scoringcomponents[65][2],
                            $scoringcomponents[66][2],
                            $scoringcomponents[67][2],
                            $scoringcomponents[69][2],
                            $scoringcomponents[68][2],
                        ];
                    } else {

                        $scores = [
                            0, 0, 0, 0, 0, 0,
                            0, 0, 0, 0, 0, 0,
                            0, 0, 0, 0, 0, 0,
                        ];
                    }
                    break;
                case 25: //MAHC
                    if (isset($scoringcomponents)) {
                        $scores = [
                            //MAHC 2022-23
                            $scoringcomponents[70][0], //low scale quality
                            $scoringcomponents[71][0], //low scale intonation
                            $scoringcomponents[72][0], //high scale quality
                            $scoringcomponents[73][0], //high scale intonation
                            $scoringcomponents[94][0], //solo quality
                            $scoringcomponents[95][0], //solo intonation
                            $scoringcomponents[96][0], //solo musicianship

                            $scoringcomponents[70][1], //...
                            $scoringcomponents[71][1],
                            $scoringcomponents[72][1],
                            $scoringcomponents[73][1],
                            $scoringcomponents[94][1],
                            $scoringcomponents[95][1],
                            $scoringcomponents[96][1],

                            $scoringcomponents[70][2],
                            $scoringcomponents[71][2],
                            $scoringcomponents[72][2],
                            $scoringcomponents[73][2],
                            $scoringcomponents[94][2],
                            $scoringcomponents[95][2],
                            $scoringcomponents[96][2],
                        ];
                    } else {

                        $scores = [
                            0, 0, 0, 0, 0, 0, 0,
                            0, 0, 0, 0, 0, 0, 0,
                            0, 0, 0, 0, 0, 0, 0,
                        ];
                    }
                    break;

                default: //NJ All-State
                    $scores = [
                        $scoringcomponents[48][0], //judge 1, Scales Quality
                        $scoringcomponents[49][0], //judge 1, Scales Low Scales
                        $scoringcomponents[50][0], //judge 1, Scales High Scales
                        $scoringcomponents[51][0], //judge 1, Scales Chromatic Scales
                        $scoringcomponents[58][0], //judge 1, Solo Quality
                        $scoringcomponents[59][0], //judge 1, Solo Intonation
                        $scoringcomponents[60][0], //judge 1, Solo Musicianship
                        $scoringcomponents[61][0], //judge 1, Quintet Quality
                        $scoringcomponents[62][0], //judge 1, Quintet Intonation
                        $scoringcomponents[63][0], //judge 1, Quintet Musicianship

                        $scoringcomponents[48][1], //judge 2, Scales Quality
                        $scoringcomponents[49][1], // etc.
                        $scoringcomponents[50][1],
                        $scoringcomponents[51][1],
                        $scoringcomponents[58][1],
                        $scoringcomponents[59][1],
                        $scoringcomponents[60][1],
                        $scoringcomponents[61][1],
                        $scoringcomponents[62][1],
                        $scoringcomponents[63][1],

                        $scoringcomponents[48][2],
                        $scoringcomponents[49][2],
                        $scoringcomponents[50][2],
                        $scoringcomponents[51][2],
                        $scoringcomponents[58][2],
                        $scoringcomponents[59][2],
                        $scoringcomponents[60][2],
                        $scoringcomponents[61][2],
                        $scoringcomponents[62][2],
                        $scoringcomponents[63][2],
                    ];
            }
        }else{ //no show
            //hard-coded for cjmes
            $scores = array_fill(0,18,0);
        }

        return $scores;
    }

}
