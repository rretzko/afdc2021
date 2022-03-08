<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Eventversion;
use App\Models\Teacher;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class AcknowledgedteachersController extends Controller
{
    public function index()
    {
        return view('eventadministration.acknowledgedteachers.index',
            [
                'eventversion' => Eventversion::find(Userconfig::getValue('eventversion', auth()->id())),
                'teachers' => Teacher::get()->where('isAcknowledged',true)->sortBy('person.alphaName'),
            ]
        );
    }
}
