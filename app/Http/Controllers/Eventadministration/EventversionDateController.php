<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Eventversiondate;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class EventversionDateController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion',auth()->id()));

        return view('eventadministration.eventversions.eventversion.dates.edit',
        [
           'eventversion' => $eventversion,
           'dates' => $eventversion->eventversiondates,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $eventversionid = Userconfig::getValue('eventversion', auth()->id());

        $inputs = $request->validate(
            [
                'datetype_ids' => ['array','required','min:21','max:21'],
                'datetype_ids.1' => ['date','required'],
            ]
        );

        foreach($inputs['datetype_ids'] AS $datetypeid => $date){

            Eventversiondate::updateOrCreate(
                [
                    'eventversion_id' => $eventversionid,
                    'datetype_id' => $datetypeid
                ],
                [
                    'dt' => $date,
                ]
            );
        }

        return redirect()->back()->with('status','Dates are updated.');
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
