<?php

namespace App\Http\Controllers\SuperAdministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\Room;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class LoadFakeScoresForTestingController extends Controller
{
    public function edit()
    {
        $default = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('sa.loadfakescoresfortestings.edit',
            [
                'eventversion' => $default,
                'eventversions' => Eventversion::all(),
                'registrants_count' => $this->registrantsCount($default->id),
                'rooms' => Room::where('eventversion_id', $default->id)->get(),
                'rooms_count' => $this->roomsCount($default->id),
            ]);
    }

    public function store(Room $room)
    {
        if($score_count = $room->createFakeScores(Userconfig::getValue('eventversion', auth()->id()))){

            session()->flash('success', $score_count.' fake scores have been created @ '.date('g:i:s a M d').'.');
        }

        $default = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('sa.loadfakescoresfortestings.edit',
            [
                'eventversion' => $default,
                'eventversions' => Eventversion::all(),
                'registrants_count' => $this->registrantsCount($default->id),
                'rooms' => Room::where('eventversion_id', $default->id)->get(),
                'rooms_count' => $this->roomsCount($default->id),
            ]);
    }

    public function update(Request $request)
    {
       $inputs = $request->validate(
           [
               'eventversion_id' => ['required','exists:eventversions,id']
           ],
       );

       //change Userconfig for use in $this->store()
        Userconfig::updateValue('eventversion',auth()->id(), $inputs['eventversion_id']);

       $registrants_count = Registrant::where('eventversion_id', $inputs['eventversion_id'])
           ->where('registranttype_id', Registranttype::REGISTERED)
           ->count('id');
;
       $rooms_count = Room::where('eventversion_id', $inputs['eventversion_id'])
           ->count('id');

       return view('sa.loadfakescoresfortestings.edit',
           [
               'eventversions' => Eventversion::all(),
               'eventversion' => Eventversion::find($inputs['eventversion_id']),
               'registrants_count' => $registrants_count,
               'rooms_count' => $rooms_count,
               'rooms' => Room::where('eventversion_id', $inputs['eventversion_id'])->get(),
           ]);
    }

    private function registrantsCount(int $eventversion_id):int
    {
        return Registrant::where('eventversion_id', $eventversion_id)
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->count('id');
    }

    private function roomsCount(int $eventversion_id):int
    {
        return Room::where('eventversion_id', $eventversion_id)
            ->count('id');
    }
}
