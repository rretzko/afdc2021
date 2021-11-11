<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id', 'scoringcomponent_id', 'proxy_id', 'registrant_id','score', 'user_id'];

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
        $eventversion = Eventversion::find($registrant->eventversion_id);
       // $scores = Score::where('registrant_id', $registrant->id)->get();
        $scoringcomponents = Scoringcomponent::where('eventversion_id', $eventversion->id)->orderBy('order_by')-> get();
        $rooms = $this->filterRooms($eventversion, $registrant);
        $filecontenttypes = $eventversion->filecontenttypes;
        $scores = [];

        if($rooms->count()) {
            for ($i = 0; $i < $eventversion->eventversionconfig->judge_count; $i++) { //1,2,3

                foreach ($filecontenttypes as $key => $filecontenttype) { //scales, solo, quartet

                    $room = $rooms->filter(function ($room) use ($filecontenttype) {
                        return $room->filecontenttypes->contains($filecontenttype);
                    });

                    $adjudicators = $room->first()->adjudicators;

                    foreach ($filecontenttype->scoringcomponents->where('eventversion_id', $eventversion->id) as $scoringcomponent) {

                        //error avoidance if full compliment of adjudicators is not available
                        if(isset($adjudicators[$i])) {
                            $scores[] = $this->where('registrant_id', $registrant->id)
                                ->where('scoringcomponent_id', $scoringcomponent->id)
                                ->where('eventversion_id', $eventversion->id)
                                ->where('user_id', $adjudicators[$i]->user_id)
                                ->value('score');
                        }else{

                            $scores[] = 0;
                        }
                    }
                }
            }
        }

        return $scores;
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

       dd($rooms->sortBy('order_by'));
    }

}
