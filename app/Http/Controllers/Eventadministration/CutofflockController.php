<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class CutofflockController extends Controller
{
    public function update(\App\Models\Eventensemble $eventensemble)
    {
        $eventversionid = Userconfig::getValue('eventversion', auth()->id());

        $current = \App\Models\Eventensemblecutofflock::where('eventversion_id', $eventversionid)
            ->where('eventensemble_id', $eventensemble->id)
            ->first() ?? new \App\Models\Eventensemblecutofflock;

        \App\Models\Eventensemblecutofflock::updateOrCreate(
            [
                'eventversion_id' => $eventversionid,
                'eventensemble_id' => $eventensemble->id,
            ],
            [
                'locked' => ($current->locked()) ? 0 : 1,
                'user_id' => auth()->id(),
            ]
        );

        return redirect(route('eventadministrator.tabroom.cutoffs'));
    }
}
