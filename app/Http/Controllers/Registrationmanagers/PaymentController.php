<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Userconfig;
use App\Traits\CountiesTrait;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use CountiesTrait;

    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $toggle = Userconfig::getValue('counties', auth()->id());

        $targetcounties = ($toggle === 'my')
            ? $this->userCounties(auth()->id())
            : $this->geostateCounties();

        return view('registrationmanagers.payments.index',
        [
            'counties' => $this->geostateCounties(),
            'eventversion' => $eventversion,
            'mycounties' => $this->userCounties(auth()->id()),
     //       'targetcounties' => $targetcounties,
            'toggle' => $toggle,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function export(Eventversion $eventversion)
    {


        return redirect()->back();
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
