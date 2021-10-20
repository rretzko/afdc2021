<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\School;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
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
        return view('registrationmanagers.index', [
            'counties' => $this->geostateCounties(),
            'eventversion' => $eventversion,
            'mycounties' => $this->userCounties(auth()->id()),
            'toggle' => Userconfig::getValue('counties', auth()->id()),
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
            'mycounties' => $this->userCounties(auth()->id()),
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
