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
        $pdf = PDF::loadView('testpdf');

        return $pdf->download('testpdf.pdf');
    }
}
