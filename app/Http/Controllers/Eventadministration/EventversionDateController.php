<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Eventversiondate;
use App\Models\Userconfig;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventversionDateController extends Controller
{

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

            switch(strlen($date)){
                case '10':
                    $dtStr = $date.' 00:00:00';
                    break;
                case '17': //2023-03-31\T18:00
                    $dtStr = Carbon::createFromFormat('Y-m-d\TH:i',$date)->toDateTimeString();
                    break;
                default:
                    $dtStr = $date;
                    break;
            }

            Eventversiondate::updateOrCreate(
                [
                    'eventversion_id' => $eventversionid,
                    'datetype_id' => $datetypeid
                ],
                [
                    'dt' => $dtStr,
                ]
            );
        }

        return redirect()->back()->with('status','Dates are updated.');
    }
}
