<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Room;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class AuditionroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        return view('eventadministration.auditionrooms.index',
            [
                'currentinstrumentations' => $eventversion->instrumentations(),
                'eventversion' => $eventversion,
                'filecontenttypes' => $eventversion->filecontenttypes->sortBy('descr'),
                'instrumentations' => $eventversion->instrumentations(),
                'room' => new Room(),
                'rooms' => $eventversion->rooms->sortBy('order_by'),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array $inputs
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function store(array $inputs, Eventversion $eventversion)
    {
        return Room::create([
            'eventversion_id' => $eventversion->id,
            'descr' => $inputs['descr'],
            'order_by' => $inputs['order_by'],
            'tolerance' => $inputs['tolerance'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        $eventversion = Eventversion::find($room->eventversion_id);

        return view('eventadministration.auditionrooms.index',
            [
                'currentinstrumentations' => $eventversion->instrumentations(),
                'eventversion' => $eventversion,
                'filecontenttypes' => $eventversion->filecontenttypes->sortBy('descr'),
                'instrumentations' => $eventversion->instrumentations(),
                'room' => $room,
                'rooms' => $eventversion->rooms->sortBy('order_by'),
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Eventversion $eventversion
     * @param \App\Models\Room $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Eventversion $eventversion, $room="0")
    {
        //early exit
        if(isset($request->cancel)){ return $this->index($eventversion); }

        $inputs = $request->validate([
            'descr' => ['required','string'],
            'order_by' => ['required','numeric'],
            'tolerance' => ['required', 'numeric'],
            'filecontenttypes' => ['required','array'],
            'filecontenttypes.*' => ['required','numeric'],
            'instrumentations' => ['required','array'],
            'instrumentations.*' => ['required','numeric'],
        ]);

        if((int)$room && $room){

            $room = Room::find($room);
        }

        if(! $room){

            $room = new Room();
        }

        if($room->id) {

            $room->update(
                [
                    'eventversion_id' => $eventversion->id,
                    'descr' => $inputs['descr'],
                    'order_by' => $inputs['order_by'],
                    'tolerance' => $inputs['tolerance'],
                ]
            );

        }else{

            $room = $this->store($inputs,$eventversion);
        }

        $room->filecontenttypes()->sync($inputs['filecontenttypes']);

        $room->instrumentations()->sync($inputs['instrumentations']);

        return $this->index($eventversion); //redirect(route('eventadministrator.rooms'));
    }

    /**
     * Remove the specified resource from storage.
     * No soft-delete; change eventversion_id to 1 to hide
     *
     * @param  \App\Models\Room $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        $eventversion = Eventversion::find($room->eventversion_id);

        $room->update(
            [
                'eventversion_id' => 1
            ]
        );

        return $this->index($eventversion);
    }
}
