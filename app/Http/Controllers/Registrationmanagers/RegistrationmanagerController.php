<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\School;
use App\Models\SchoolVerified;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
use App\Services\ParticipatingDirectorsTableService;
use App\Traits\CountiesTrait;
use Illuminate\Http\Request;

class RegistrationmanagerController extends Controller
{
    use CountiesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $myCounties = $this->userCounties(auth()->id(),$eventversion->id);

        $service = new ParticipatingDirectorsTableService($myCounties);

        return view('registrationmanagers.index', [
            'counties' => $this->geostateCounties(),
            'eventversion' => $eventversion,
            'mycounties' => $myCounties,
            'toggle' => 'my', //Userconfig::getValue('counties', auth()->id()),
            'table' => $service->table(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $counties //my, all, or unknown
     * @return \Illuminate\Http\Response
     */
    public function show($counties)
    {
        Userconfig::updateValue('counties', auth()->id(), $counties);

        return view('registrationmanagers.index', [
            'eventversion' => Eventversion::find(Userconfig::getValue('eventversion', auth()->id())),
            'counties' => $this->geostateCounties(),
            'mycounties' => $this->userCounties(auth()->id(), ),
            'toggle' => Userconfig::getValue('counties', auth()->id()),
        ]);
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
     * @todo log these changes by registration manager
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Registrant $registrant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Registrant $registrant)
    {
        $registrant->instrumentations()->sync($request['instrumentation_id']);

        return $this->index(Eventversion::find($registrant->eventversion_id));
    }

    /**
     * Display ALL counties for user
     *
     * @return \Illuminate\Http\Response
     */
    public function allCounties()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $counties = $this->geostateCounties();

        $service = new ParticipatingDirectorsTableService($counties);

        return view('registrationmanagers.index', [
            'counties' => $counties,
            'eventversion' => $eventversion,
            'mycounties' => $counties,
            'toggle' => 'all',
            'table' => $service->table(),
        ]);
    }

    public function schoolVerified(int $schoolId)
    {
        SchoolVerified::updateOrCreate(
            [
                'eventversion_id' => Userconfig::getValue('eventversion', auth()->id()),
                'school_id' => $schoolId,
            ],
            [
                'verified' => 1,
            ]
        );

        return back();
    }

    public function schoolUnverified(int $schoolId)
    {
        SchoolVerified::updateOrCreate(
            [
                'eventversion_id' => Userconfig::getValue('eventversion', auth()->id()),
                'school_id' => $schoolId,
            ],
            [
                'verified' => 0,
            ]
        );

        return back();
    }
}
