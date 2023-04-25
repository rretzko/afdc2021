<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Fileserver;
use App\Models\Registrant;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
use Illuminate\Http\Request;

class MediadownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    {
        //$registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        return view('eventadministration.mediadownloads.index',
            [
                'eventversion' => $eventversion,
                'targetregistrant' => new Registrant(),
                //'registrants' => $registrationactivity->registeredTotal(),
            ]);
    }

    //public function download(Registrant $registrant)
    public function download(Request $request)
    {
        $inputs = $request->validate([
            'registrant_id' => ['required','exists:registrants,id'],
        ]);

        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));//$registrant->eventversion_id);

        //$registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);


        return view('eventadministration.mediadownloads.index',
            [
                'eventversion' => $eventversion,
                'targetregistrant' => Registrant::find($inputs['registrant_id']), //$registrant,
                //'registrants' => $registrationactivity->registeredTotal(),
            ]);
    }

}
