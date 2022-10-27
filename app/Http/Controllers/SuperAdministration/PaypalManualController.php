<?php

namespace App\Http\Controllers\SuperAdministration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaypalManualController extends Controller
{
    
    /**
     * Update state based on user_id
     */
    public function update(Request $request)
    {
        return view('sa.paypals.update',['registrant' => 'No registrant found.']);
    }
}
