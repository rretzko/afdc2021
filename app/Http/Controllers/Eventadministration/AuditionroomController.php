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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.auditionrooms.index',
            [
                'currentinstrumentations' => $eventversion->instrumentations(),
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
     * @return \Illuminate\Http\Response
     */
    public function store(array $inputs)
    {
        return Room::create([
            'eventversion_id' => Userconfig::getValue('eventversion', auth()->id()),
            'descr' => $inputs['descr'],
            'order_by' => $inputs['order_by']
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
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.auditionrooms.index',
            [
                'currentinstrumentations' => $eventversion->instrumentations(),
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
     * @param \App\Models\Room $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $room)
    {
        //early exit
        if(isset($request->cancel)){ return $this->index(); }

        $inputs = $request->validate([
            'descr' => ['required','string'],
            'order_by' => ['required','numeric'],
            'filecontenttypes' => ['required','array'],
            'filecontenttypes.*' => ['required','numeric'],
            'instrumentations' => ['required','array'],
            'instrumentations.*' => ['required','numeric'],
        ]);

        if(is_int($room) && $room){

            $room = Room::find($room);
        }

        if(is_int($room) && (! $room)){

            $room = new Room();
        }

        if($room->id) {

            $room->update(
                [
                    'eventversion_id' => Userconfig::getValue('eventversion', auth()->id()),
                    'descr' => $inputs['descr'],
                    'order_by' => $inputs['order_by']
                ]
            );

        }else{

            $room = $this->store($inputs);
        }

        $room->filecontenttypes()->sync($inputs['filecontenttypes']);

        $room->instrumentations()->sync($inputs['instrumentations']);

        return redirect(route('eventadministrator.rooms'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        $room->update(
            [
                'eventversion_id' => 1
            ]
        );

        return $this->index();
    }
}
