<?php

namespace App\Http\Controllers\Rehearsalmanagers;

use App\Exports\ParticipationFeeExport;
use App\Exports\PaypalReconciliationExport;
use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Userconfig;
use App\Models\Utility\AcceptedParticipants;
use App\Models\Utility\AcceptedSchools;
use App\Models\Utility\PaypalParticipationFees;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PaypalReconciliationController extends Controller
{
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $participation_fees = PaypalParticipationFees::byEventversion($eventversion->id);

        return view('rehearsalmanagers.paypalparticipationfees.index',
            compact( 'eventversion', 'participation_fees')
        );
    }

    public function export()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $participation_fees = PaypalParticipationFees::byEventversion($eventversion->id);

        $export = new PaypalReconciliationExport($participation_fees);

        $datetime = date('Ynd_Gis');

        return Excel::download($export, 'paypalReconciliation_'.$datetime.'.csv');
    }
}
