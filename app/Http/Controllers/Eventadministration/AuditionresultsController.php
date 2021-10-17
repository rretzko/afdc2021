<?php

namespace App\Http\Controllers\Eventadministration;

use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\Scoringcomponent;
use App\Models\Userconfig;
use App\Traits\CompletedAdjudicationsTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditionresultsController extends Controller
{
    use CompletedAdjudicationsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        //$completes = $this->completedAdjudications($eventversion);

        return view('eventadministration.auditionresults.index',
        [
            //'completes' => $completes,
            'eventversion' => $eventversion,
           // 'registrants' => Registrant::where('eventversion_id', $eventversion->id)
           //     ->where('registranttype_id', Registranttype::REGISTERED)
            //    ->get(),
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
    public function show(\App\Models\Instrumentation $instrumentation)
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $completes = $this->completedAdjudicationsByInstrumentation($instrumentation);

        $filtered = $this->filterRegistrants($eventversion, $instrumentation);

        return view('eventadministration.auditionresults.index',
            [
                'completes' => $completes,
                'eventversion' => $eventversion,
                'registrants' => $filtered,
                'score' => new \App\Models\Score,
                'scoresummary' => new \App\Models\Scoresummary,
                'scoringcomponents' => Scoringcomponent::where('eventversion_id', $eventversion->id)->get(),
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

    private function filterRegistrants($eventversion, $instrumentation)
    {
        $registrants = Registrant::where('eventversion_id', $eventversion->id)
        ->where('registranttype_id', Registranttype::REGISTERED)
        ->get();

        $filtered = $registrants->filter(function($registrant) use($instrumentation){

            return $registrant->instrumentations->contains($instrumentation);
        });

        //sort by grandtotal
        $a = [];
        foreach($filtered AS $item){

            $a[] = ['grandtotal' => $item->grandtotal(), 'registrant' => $item];
        }

        asort($a,);

        return collect(array_column($a, 'registrant'));
    }
}
