<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Exports\ParticipantsExport;
use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Timeslot;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TimeslotassignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        return view('registrationmanagers.timeslots.index',
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
        //
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
    public function update(Request $request)
    {
        $input = $request->validate([
           'eventversion_id' => ['required', 'numeric'],
           'school_id' => ['required', 'numeric'],
           'timeslot' => ['required', 'string'],
        ]);

        Timeslot::updateOrCreate([
            'eventversion_id' => $input['eventversion_id'],
            'school_id' => $input['school_id'],
            ],
            [
            'armytime' => $this->convertToArmytime($input['timeslot']),
            'timeslot' => $input['timeslot'],
            ]
        );

        return $this->index(Eventversion::find($input['eventversion_id']));

    }

    public function download(Eventversion $eventversion)
    {
        $timeslots = new \App\Exports\TimeslotsExport($eventversion);

        return Excel::download($timeslots, 'timeslots.csv');
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

    private function convertToArmytime($string)
    {
        $poscolon = strpos($string, ':');

        $hour = substr($string,0,$poscolon);
        $minute = substr($string, ($poscolon + 1), 2);

        $hour = ($hour < 8) ? ($hour + 12) : $hour;

        return $hour.$minute;
    }
}
