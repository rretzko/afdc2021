<?php

namespace App\Http\Controllers\Afdcauth;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
use App\Models\Eventrole;
use App\Models\Eventversionrole;
use App\Models\Userconfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('afdcauths.login');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required','string'],
            'password' => ['required', 'string'],
        ]);

        //$registrationmanagers = [45,56,249,423,21,368]; //Retzko, Breitzman,  Markowski, Lal

        if(Auth::attempt($credentials)){

            $eventroles = Eventrole::where('user_id', auth()->id())->get();

            if($eventroles->count()){

                //return redirect()->route('registrationmanagers.index');
                return view('home',
                [
                    'eventroles' => $eventroles,
                ]);

            }else {

                return view('dashboard',
                    [
                        'dashboard' => new Dashboard,
                    ]);
            }
        }
dd(__LINE__);
        return redirect()->route('login');
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


}
