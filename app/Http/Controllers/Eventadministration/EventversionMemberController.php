<?php

namespace App\Http\Controllers\Eventadministration;

use App\Exports\MembershipExport;
use App\Http\Controllers\Controller;
use App\Models\Datetype;
use App\Models\Eventversion;
use App\Models\Membership;
use App\Models\Membershiptype;
use App\Models\Person;
use App\Models\Subscriberemail;
use App\Models\Userconfig;
use App\Services\MembershipTableService;
use App\Services\SubscriberMatchService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
     * @param Request $request
     * @param int $id //user_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $id)
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $organizationid = $eventversion->event->organization->id;
        $person = Person::find($id);

        $trashed = Membership::withTrashed()
            ->where('user_id', $id)
            ->where('organization_id', $organizationid)
            ->first();

        //restore trashed membership
        if ($trashed) {

            $trashed->restore();

        } else {

            Membership::create(
                [
                    'user_id' => $id,
                    'organization_id' => $organizationid,
                    'membershiptype_id' => Membershiptype::ACTIVE,
                    'membership_id' => 'unknown',
                    'expiration' => $eventversion->eventversiondates->where('datetype_id', Datetype::MEMBERSHIP_VALID)->first()->dt,
                ]
            );
        }

        $request->session()->flash('status', '"' . $person->fullnameAlpha() . '" has been added.');

        return $this->edit();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Membership $membership)
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $organization = $eventversion->event->organization;

        return view('eventadministration.memberships.edit',
            [
                'eventversion' => $eventversion,
                'membership' => $membership,
                'membershiptypes' => Membershiptype::all(),
                'organization' => $organization,
                'person' => $membership->user->person,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));

        $service = new MembershipTableService;

        return view('eventadministration.eventversions.eventversion.members.edit',
            [
                'eventversion' => $eventversion,
                'matchesfound' => $service->datatable(),
                'members' => $eventversion->members,
            ]);
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
                'membershiptype_id' => ['numeric', 'required', 'exists:membershiptypes,id'],
                'membership_id' => ['string', 'nullable'],
                'expiration' => ['date', 'nullable'],
                'grade_levels' => ['string', 'nullable'],
                'subjects' => ['string', 'nullable'],
            ]
        );

        $membership->update($inputs);

        return $this->edit();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id //memberships id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        $person = Person::find(Membership::find($id)->user_id);

        Membership::destroy($id);

        $request->session()->flash('status', '"' . $person->fullnameAlpha() . '" has been removed.');

        return $this->edit();
    }

    public function export()
    {
        $membership = new MembershipExport(Eventversion::find(Userconfig::getValue('eventversion', auth()->id())));

        $datetime = date('Ynd_Gis');

        return Excel::download($membership, 'membership_'.$datetime.'.csv');
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
