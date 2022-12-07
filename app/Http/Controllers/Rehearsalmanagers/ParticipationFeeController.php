<?php

namespace App\Http\Controllers\Rehearsalmanagers;

use App\Exports\ParticipationFeeExport;
use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Userconfig;
use App\Models\Utility\AcceptedParticipants;
use App\Models\Utility\AcceptedSchools;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ParticipationFeeController extends Controller
{
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $schools = AcceptedSchools::byEventversion($eventversion->id);
        $acceptances = AcceptedParticipants::countsBySchoolWithPayPalPayments($eventversion, $schools); //return array
        $count_total = AcceptedParticipants::$count_total;
        $sum_balance_due = number_format(AcceptedParticipants::$sum_balance_due, 2);
        $sum_students = number_format(AcceptedParticipants::$sum_paypal_students, 2);
        $sum_teachers = number_format(AcceptedParticipants::$sum_paypal_teachers, 2);

        return view('rehearsalmanagers.participationfees.index',
            compact( 'acceptances', 'count_total', 'eventversion',
                'sum_balance_due','sum_students','sum_teachers')
        );
    }

    public function export()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $schools = AcceptedSchools::byEventversion($eventversion->id);
        $acceptances = AcceptedParticipants::countsBySchoolWithPayPalPayments($eventversion, $schools); //return array

        $export = new ParticipationFeeExport($acceptances);

        $datetime = date('Ynd_Gis');

        return Excel::download($export, 'participationFees_'.$datetime.'.csv');
    }
}
