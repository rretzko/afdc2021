<?php

namespace App\Http\Controllers\SuperAdministration;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registrant;
use Illuminate\Http\Request;

class PaypalManualController extends Controller
{

    /**
     * Display form
     */
    public function edit()
    {
        return view('sa.paypals.edit',['registrant' => 'No registrant found.']);
    }

    /**
     * Update state based on user_id
     */
    public function update(Request $request)
    {
        $payments = $this->parseString($request['paypal-string']);

        if($payments->count() && ($payments->count() === 1)){

            $result = 'One payment found for registrant_id: '.$request['paypal-string'];
        }elseif($payments->count() && ($payments->count() > 1)){

            $result = $payments->count().' payments found for registrant_id: '.$request['paypal-string'];
        }else{

            $result = 'No payments found for registrant_id: '.$request['paypal-string'];
        }

        return view('sa.paypals.edit',['registrant' => $result]);
    }

    /**
     * @param $str teacher*registrant*eventversion*school*amount
     * @return void
     */
    private function parseString($str)
    {
        $parts = explode('*',$str);

        Payment::create(
        [
            'user_id' => $parts[0],
            'eventversion_id' => $parts[2],
            'school_id' => $parts[3],
            'registrant_id' => $parts[1],
            'paymenttype_id' => 3, //PayPal
            'amount' => $parts[4],
            'updated_by' => auth()->id(),
            'vendor_id' => 'manual update: '.date('Y-m-d G:i:s'),
        ]
    );

        return Payment::where('registrant_id', $parts[1])->get();
    }
}
