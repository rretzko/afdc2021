<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Datetype;
use App\Models\Eventversion;
use App\Models\Eventversiondate;
use App\Models\User;
use App\Models\Userconfig;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublishresultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $currentdt = $eventversion->eventversiondates->where('datetype_id', Datetype::RESULTS_RELEASE)->first()->dt;

        return view('eventadministration.publishresults.index',
            [
                'currentdt' => $currentdt,
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

     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $current = $eventversion->eventversiondates->where('datetype_id', Datetype::RESULTS_RELEASE)->first();

        if($current->dt){ //unset date
            $current->update(['dt' => NULL,]);
        }else{
            $current->update(['dt' => Carbon::now()]);
        }

        return $this->index();
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