<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\School;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class RegistrationmanagerController extends Controller
{
    private $counties;
    private $mycounties;

    public function __construct()
    {
        $this->counties = [1,2,3,4,5,6,7,8,9,10,
            11,12,13,14,15,16,17,18,19,20,21];
        $this->mycounties = [
            45 => [1,6,7,9,15,17,19,],
            56 => [4,11,12,16,20,],
            249 => [5,8,10,21,13,],
            435 => [2,3,14,18,]
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('registrationmanagers.index', [
            'counties' => $this->counties,
            'mycounties' => $this->mycounties,
            'myschools' => $eventversion->schoolsByCounties($this->mycounties[auth()->id()]),
            'schools' => $eventversion->schools(),
            'toggle' => Userconfig::getValue('counties', auth()->id()) ?: 'my',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $counties
     * @return \Illuminate\Http\Response
     */
    public function show($counties)
    {
        Userconfig::updateValue('counties', auth()->id(), $counties);

        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('registrationmanagers.index', [
            'counties' => $this->counties,
            'mycounties' => $this->mycounties,
            'myschools' => $eventversion->schoolsByCounties($this->mycounties[auth()->id()]),
            'schools' => $eventversion->schools(),
            'toggle' => Userconfig::getValue('counties', auth()->id()) ?: 'my',
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

    private function mySchools($counties)
    {
        return School::whereIn('county_id',$counties)->get();
    }

    private function schools($counties)
    {
        return collect();
    }
}
