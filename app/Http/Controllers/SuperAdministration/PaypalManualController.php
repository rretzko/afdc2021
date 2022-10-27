<?php

namespace App\Http\Controllers\SuperAdministration;

use App\Http\Controllers\Controller;
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
        return redirect()->route('sa.paypals.update',['registrant' => 'No registrant found.']);
    }
}
