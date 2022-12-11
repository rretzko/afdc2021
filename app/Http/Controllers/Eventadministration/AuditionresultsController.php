<?php

namespace App\Http\Controllers\Eventadministration;

use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\Scoresummary;
use App\Models\Scoringcomponent;
use App\Models\Userconfig;
use App\Traits\CompletedAdjudicationsTrait;
use App\Traits\IncompleteAdjudicationsTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditionresultsController extends Controller
{
    use CompletedAdjudicationsTrait, IncompleteAdjudicationsTrait;

    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $completes = Scoresummary::where('eventversion_id', $eventversion->id)->where('result', '<>', 'inc')->get();
        $noshows = Scoresummary::where('eventversion_id', $eventversion->id)->where('result', '=', 'n/s')->get();
        $incompletes = Scoresummary::where('eventversion_id', $eventversion->id)->where('result', '=', 'inc')->get();

        return view('eventadministration.auditionresults.index',
        [
            'completes' => $completes,
            'eventversion' => $eventversion,
            'incompletes' => $incompletes,
            'summary_page' => true,
            'noshows' => $noshows,
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
     * @param  \App\Models\Eventversion $eventversion
     * @param  \App\Models\Instrumentation $instrumentation
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Models\Eventversion $eventversion, \App\Models\Instrumentation $instrumentation)
    {
        set_time_limit(120);

        //$noshows = $this->noShowAdjudicationByInstrumentation($eventversion, $instrumentation);

        $incompletes = $this->incompleteAdjudicationsByInstrumentation($eventversion, $instrumentation);

        //return Scoresummary objects for each completed audition
        $completes = $this->completedAdjudicationsByInstrumentation($eventversion, $instrumentation);

        $filtered = $this->filterRegistrants($eventversion, $instrumentation);

        return view('eventadministration.auditionresults.index',
            [
                'completes' => $completes,
                'eventversion' => $eventversion,
                'incompletes' => $incompletes,
                'targetinstrumentation' => $instrumentation,
                'registrants' => $filtered,
                'score' => new \App\Models\Score,
                'scoresummary' => new \App\Models\Scoresummary,
                'scoringcomponents' => Scoringcomponent::where('eventversion_id', $eventversion->id)->orderBy('order_by')->get(),
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

    /**
     * Return collection of registered Registrants in asc/desc grandtotal order
     * @param $eventversion
     * @param $instrumentation
     * @return \Illuminate\Support\Collection
     */
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
        foreach($filtered AS $item){ //$item = registrant

            $a[] = ['grandtotal' => $item->grandtotal(), 'registrant' => $item];
        }

        ($eventversion->eventversionconfig->bestscore === 'asc')
            ? asort($a,)
            : arsort($a);

        return collect(array_column($a, 'registrant'));
    }
}
