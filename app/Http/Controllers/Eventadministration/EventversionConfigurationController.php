<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Eventversionconfig;
use App\Models\Eventversiondate;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class EventversionConfigurationController extends Controller
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

        return view('eventadministration.eventversions.eventversion.configurations.edit',
        [
           'eventversion' => $eventversion,
           'configurations' => $eventversion->eventversionconfig,
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
                'alternating_scores' => ['nullable','numeric','min:1','max:1'],
                'audiofiles' => ['nullable','numeric','min:1','max:1'],
                'bestscore' => ['required','string', 'min:3','max:4'],
                'eapplication' => ['nullable','numeric','min:1','max:1'],
                'epaymentsurcharge' => ['nullable','string'],
                'expiration' => ['nullable','date'],
                'grades' => ['required','array','min:1','max:12'],
                'grades.*' => ['nullable','numeric'],
                'instrumentation_count' => ['required','numeric','min:1','max:50'],
                'judge_count' => ['required','numeric','min:1','max:10'],
                'max_count' => ['required','numeric','min:0','max:50'],
                'max_uppervoice_count' => ['required','numeric','min:0','max:50'],
                'membershipcard' => ['nullable','numeric','min:1','max:1'],
                'paypalstudent' => ['nullable','numeric','min:1','max:1'],
                'paypalteacher' => ['nullable'],
                'registrationfee' => ['nullable','string'],
                'onsiteregistrationfee' => ['nullable','string'],
                'videofiles' => ['nullable','numeric','min:1','max:1'],
                'virtualaudition' => ['nullable','numeric','min:1','max:1'],
            ]
        );

        Eventversionconfig::updateOrCreate(
            [
                'eventversion_id' => $eventversionid
            ],
            [
                'paypalteacher' => array_key_exists('paypalteacher', $inputs) ?: 0,
                'paypalstudent' => array_key_exists('paypalstudent', $inputs) ?: 0,
                'registrationfee' => $this->usCurrency($inputs['registrationfee']) ?: 0.00,
                'onsiteregistrationfee' => $this->usCurrency($inputs['onsiteregistrationfee']) ?: 0,
                'grades' => implode(',', $inputs['grades']),
                'eapplication' => array_key_exists('eapplication', $inputs) ?: 0,
                'judge_count' => $inputs['judge_count'],
                'max_count' => $inputs['max_count'],
                'max_uppervoice_count' => $inputs['max_uppervoice_count'],
                'missing_judge_average' => 0, //deliberately hard-coded until this function can be implemented
                'epaymentsurcharge' => $this->usCurrency($inputs['epaymentsurcharge']) ?: 0,
                'virtualaudition' => array_key_exists('virtualaudition', $inputs) ?: 0,
                'audiofiles' => array_key_exists('audiofiles', $inputs) ?: 0,
                'videofiles' => array_key_exists('videofiles',$inputs) ?: 0,
                'bestscore' => $inputs['bestscore'],
                'membershipcard' => array_key_exists('membershipcard', $inputs) ?: 0,
                'instrumentation_count' => $inputs['instrumentation_count'],
                'alternating_scores' => array_key_exists('alternating_scores', $inputs) ?: 0,
            ]
        );

        return redirect()->back()->with('status','Configurations are updated.');
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

    /**
     * Convert string values into two-decimal dollars and cents
     */
    private function usCurrency($str): float
    {
        //early exit
        if(! $str){ return 0.00;}

        $parts = explode('.', $str);

        if(! key_exists(1, $parts)){ $parts[1] = 0;}

        return floatval(implode('.',$parts));
    }
}
