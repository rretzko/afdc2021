<?php

namespace App\Http\Controllers\Rehearsalmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\Userconfig;
use App\Models\Utility\AcceptedParticipants;
use Illuminate\Http\Request;

class RemovedStudentRosterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $student = NULL;
        $acceptedParticipants = AcceptedParticipants::acceptedParticipantNamesArray($eventversion);
        $removeds = Registrant::where('eventversion_id', $eventversion->id)
            ->where('registranttype_id', Registranttype::REMOVED)
            ->get()
            ->sortBy('fullNameAlpha');

        return view('rehearsalmanagers.removedstudentsroster.index',
            compact('acceptedParticipants','eventversion','removeds','student')
        );
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->validate(
            [
                'registrant_id' => ['required','exists:registrants,id'],
            ],
        );

        $registrant = Registrant::find($inputs['registrant_id']);

        $registrant->update(
            [
                'registranttype_id' => Registranttype::REMOVED,
            ]
        );

        return back();
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
