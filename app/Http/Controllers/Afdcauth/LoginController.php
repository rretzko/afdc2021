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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        $registrationmanagers = [45,56,249,423,21]; //Retzko, Breitzman,  Markowski, Lal

        if(Auth::attempt($credentials)){

            /**
             * All of the following should be (maybe) assigned to a roles event
             */
            if(in_array(auth()->id(), $registrationmanagers )){

                $eventroles = Eventrole::where('user_id', auth()->id())->get();

                Userconfig::updateValue('eventversion', auth()->id(), 65); //2021 NJ All-State Chorus
                Userconfig::updateValue('event', auth()->id(), 9); //NJ All-State Chorus
                Userconfig::updateValue('organization', auth()->id(), 3); //NJMEA

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
