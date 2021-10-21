<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\School;
use App\Models\Userconfig;
use App\Traits\CountiesTrait;
use Illuminate\Http\Request;

class RegistrantschoolController extends Controller
{
    use CountiesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param Eventversion $eventversion
     * @param  School $school
     * @return \Illuminate\Http\Response
     */
    public function show(Eventversion $eventversion, School $school)
    {
        return view('registrationmanagers.registrants.school.show',
            [
                'school' => $school,
                //'registrants' => $eventversion->registrantsForSchool($school),
                'counties' => $this->geostateCounties(),
                'eventversion' => $eventversion,
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
