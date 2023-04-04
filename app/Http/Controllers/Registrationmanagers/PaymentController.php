<?php

namespace App\Http\Controllers\Registrationmanagers;

use App\Exports\SchoolPaymentsExport;
use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Userconfig;
use App\Traits\CountiesTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    use CountiesTrait;

    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        $toggle = Userconfig::getValue('counties', auth()->id());

        $targetcounties = ($toggle === 'my')
            ? $this->userCounties(auth()->id())
            : $this->geostateCounties();

        return view('registrationmanagers.payments.index',
        [
            'counties' => $this->geostateCounties(),
            'eventversion' => $eventversion,
            'mycounties' => $this->userCounties(auth()->id()),
     //       'targetcounties' => $targetcounties,
            'toggle' => $toggle,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function export(Eventversion $eventversion)
    {
        return Excel::download(new SchoolPaymentsExport, 'schoolPayments_' . date('Ymd_Gis') . '.csv');
    }

}
