<?php

namespace App\Http\Controllers\Eventadministration;


use App\Exports\ParticipantsExport;
use App\Http\Controllers\Controller;
use App\Models\Eventensemble;
use App\Models\Eventversion;
use Maatwebsite\Excel\Facades\Excel;

class CsvParticipatingstudentsController extends Controller
{
    /**
     * @param Eventensemble $eventensemble
     * @param Eventversion $eventversion
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function index(Eventversion $eventversion, Eventensemble $eventensemble)
    {
        $participants = new ParticipantsExport($eventversion, $eventensemble);

        return Excel::download($participants, 'participants.csv');
    }
}
