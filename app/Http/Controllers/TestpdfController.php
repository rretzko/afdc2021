<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade AS PDF;
use Illuminate\Http\Request;

class TestpdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*
         *  $pdf = PDF::loadView('pdfs.auditionresults.'//9.65.2021_22_auditionresults',
            . $eventversion->event->id
            .'.'
            . $eventversion->id
            . '.auditionresults',
            compact('eventversion','registrants', 'scoringcomponents','score','scoresummary'))
            ->setPaper('letter', 'portrait');;
         */
        $pdf = PDF::loadView('pdfs.auditionresults.11.67.testpdf');

        return $pdf->download('testpdf_auditionresults_11_67.pdf');
    }
}
