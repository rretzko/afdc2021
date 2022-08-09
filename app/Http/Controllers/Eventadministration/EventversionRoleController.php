<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Eventversionrole;
use App\Models\Membership;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class EventversionRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.eventversions.eventversion.roles.index',
        [
           'eventversion' => $eventversion,
           'memberships' => $eventversion->event->organization->memberships,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param \Illuminate\Http\Request $request
     * @param Membership $membership
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Membership $membership)
    {
        $inputs = $request->validate(
            [
                'roles' => ['nullable','array'],
                'roles.*' => ['required','numeric','exists:roletypes,id'],
            ]
        );

        $eventversionid = Userconfig::getValue('eventversion', auth()->id());
        $membershipid = $membership->membership_id;
        $userid = $membership->user_id;

        //remove all current roles
        $curs = Eventversionrole::where('eventversion_id',$eventversionid)
            ->where('user_id', $userid)
            ->get();

        Eventversionrole::destroy($curs);

        //add new roles
        if(count($inputs)){
            foreach($inputs['roles'] AS $roletype){

                Eventversionrole::create(
                    [
                        'eventversion_id' => $eventversionid,
                        'user_id' => $userid,
                        'membership_id' => $membership->id,
                        'roletype_id' => $roletype,
                    ]
                );
            }
        }

        return redirect()->back()->with('status', 'Roles updated for "'.$membership->user->person->fullnameAlpha().'"');
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
