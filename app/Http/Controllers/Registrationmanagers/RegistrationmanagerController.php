<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\School;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
use Illuminate\Http\Request;

class RegistrationmanagerController extends Controller
{
    private $counties;
    private $mycounties;

    public function __construct()
    {
        $this->counties = [1,2,3,4,5,6,7,8,9,10, //includes "unknown" county
            11,12,13,14,15,16,17,18,19,20,21,22];
        $this->mycounties = [
            45 => [1,6,7,9,15,17,19,],
            56 => [4,11,12,16,20,],
            249 => [5,8,10,21,13,],
            423 => [2,3,14,18,]
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('registrationmanagers.index', [
            'counties' => $this->counties,
            'eventversion' => Eventversion::find(Userconfig::getValue('eventversion', auth()->id())),
            'mycounties' => $this->mycounties[auth()->id()],
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
            'counties' => $this->counties,
            'mycounties' => $this->mycounties[auth()->id()],
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
