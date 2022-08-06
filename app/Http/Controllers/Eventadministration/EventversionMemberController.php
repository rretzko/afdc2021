<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Subscriberemail;
use App\Models\Userconfig;
use App\Services\SubscriberMatchService;
use Illuminate\Http\Request;

class EventversionMemberController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        return view('eventadministration.eventversions.eventversion.members.edit',
            [
                'eventversion' => $eventversion,
                'matchesfound' => '',
                'members' => $eventversion->members,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    /**
     * Show the form for editing the specified resource with a search value.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $inputs = $request->validate(
            [
                'search' => ['string','required', 'min:3'],
            ]
        );

        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        $service = new SubscriberMatchService($this->searchEmails($inputs['search']));

        return view('eventadministration.eventversions.eventversion.members.edit',
            [
                'matchesfound' => $service->table(),
                'eventversion' => $eventversion,
                'members' => $eventversion->members,
            ]);
    }

    private function searchEmails($search)
    {
        $emails = Subscriberemail::all()->filter(function ($email) use($search){

            //find all emails with $search excluding bogus '.ru' emails
           return str_contains($email->email,$search) && (! str_contains($email->email,'.ru'));
        });

        return $emails;
    }

}
