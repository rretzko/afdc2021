<?php

namespace App\Http\Controllers\SuperAdministration;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class LogInAsController extends Controller
{
    /**
     * Show form for select user to log in as
     */
    public function edit()
    {
        $collection = Teacher::with('person')->get()->sortBy('person.last');

        $teachers = [];
        foreach($collection AS $teacher){
            $teachers[] = [$teacher['person']->user_id,$teacher['person']->fullNameAlpha()];
        }

        //sa = SuperAdministrator
        return view('sa.loginas.edit', compact('teachers'));
    }

    /**
     * Update state based on user_id
     */
    public function update(Request $request)
    {
        $input = $request->validate(
            [
                'user_id' => ['numeric','required','exists:people']
            ]
        );

        auth()->loginUsingId($input['user_id']);

        return redirect()->route('home');
    }
}
