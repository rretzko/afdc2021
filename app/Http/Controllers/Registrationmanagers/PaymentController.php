<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class PaymentController extends Controller
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
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $toggle = Userconfig::getValue('counties', auth()->id());

        $targetcounties = ($toggle === 'my')
            ? $this->mycounties[auth()->id()]
            : $this->counties;

        return view('registrationmanagers.payments.index',
        [
            'counties' => $this->counties,
            'eventversion' => $eventversion,
            'mycounties' => $this->mycounties,
            'schools' => $eventversion->participatingSchools,
            'targetcounties' => $targetcounties,
            'toggle' => $toggle,
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
