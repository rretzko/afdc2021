<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\Scoringcomponent;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class ReportsAuditionresultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventverion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $filename = self::build_Filename($eventversion);
        $registrants = $this->filterRegistrants($eventversion);
        $scoringcomponents = Scoringcomponent::where('eventversion_id', $eventversion->id)->get();
        $score = new \App\Models\Score;
        $scoresummary =  new \App\Models\Scoresummary;

        //ex. pages.pdfs.applications.12.64.application
        $pdf = PDF::loadView('pdfs.auditionresults.'//9.65.2021_22_auditionresults',
            . $eventversion->event->id
            .'.'
            . $eventversion->id
            . '.auditionresults',
            compact('eventversion','registrants', 'scoringcomponents','score','scoresummary'))
            ->setPaper('letter', 'portrait');;

        //log application printing
        //Application::create([
        //    'registrant_id' => $registrant->id,
       // //    'updated_by' => auth()->id(),
       // ]);

        return $pdf->download($filename);
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

    private function build_Filename(Eventversion $eventversion) : string
    {
        return str_replace(' ', '_', //'2022_NJASC_2022.pdf';
                str_replace('.', '', $eventversion->short_name))
            . '_'
            . $eventversion->senior_class_of
            . '_'
           . '.pdf';
    }

    private function filterRegistrants($eventversion)
    {
        $registrants = [];

        $unsorted = Registrant::where('eventversion_id', $eventversion->id)
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->get();

        foreach($eventversion->instrumentations() AS $instrumentation){

            $filtered = $unsorted->filter(function($registrant) use($instrumentation){

                return $registrant->instrumentations->contains($instrumentation);
            });

            //sort by grandtotal
            $a = [];
            foreach($filtered AS $item){

                $a[] = ['grandtotal' => $item->grandtotal(), 'registrant' => $item];
            }

            asort($a,);

            $registrants[$instrumentation->descr] = collect(array_column($a, 'registrant'));
        }
dd($registrants);
        return $registrants;
    }
}
