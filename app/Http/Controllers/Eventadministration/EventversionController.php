<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Eventversion;
use App\Models\Eventversionrole;
use App\Models\Eventversiontype;
use App\Models\Gradetype;
use App\Models\Membership;
use App\Models\Roletype;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class EventversionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        Userconfig::updateValue('eventversion', auth()->id(), $eventversion->id);

        return view('eventadministration.eventversions.eventversion.index',
            [
                'eventversion' => $eventversion,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('eventadministration.eventversions.eventversion.create',
            [
                'event' => Event::find(Userconfig::getValue('event', auth()->id())),
                'eventversiontypes' => Eventversiontype::orderBy('descr')->get(),
                'grades' => Gradetype::orderBy('orderby')->get()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $eventid = Userconfig::getValue('event', auth()->id());

        $inputs = $request->validate([
           'name' => ['string','required', 'min:8'],
           'short_name' => ['string', 'required', 'min:3'],
           'eventversiontype_id' => ['numeric', 'required', 'exists:eventversiontypes,id'],
            'senior_class_of' => ['numeric','required', 'min:2010'],
           'grades' => ['array','required', 'min:1'],
        ]);

        $ev = Eventversion::create([
           'event_id' => $eventid,
           'name' => $inputs['name'],
           'short_name' => $inputs['short_name'],
           'senior_class_of' => $inputs['senior_class_of'],
           'eventversiontype_id' => $inputs['eventversiontype_id'],
           'grades' => implode(',',$inputs['grades']),
        ]);

        //add administrator role
        Eventversionrole::create(
            [
                'eventversion_id' => $ev->id,
                'user_id' => auth()->id(),
                'membership_id' => Membership::where('user_id', auth()->id())->first()->id,
                'roletype_id' => Roletype::EVENT_ADMINISTRATOR,
            ],
        );

        return view('eventadministration.index', ['event' => Event::find($eventid),'eventversion' => $ev]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
