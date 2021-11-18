<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Userconfig;
use Illuminate\Http\Request;

class CutofflockController extends Controller
{
    public function update(\App\Models\Eventversion $eventversion, \App\Models\Eventensemble $eventensemble)
    {
        $current = \App\Models\Eventensemblecutofflock::where('eventversion_id', $eventversion->id)
            ->where('eventensemble_id', $eventensemble->id)
            ->first() ?? new \App\Models\Eventensemblecutofflock;

        \App\Models\Eventensemblecutofflock::updateOrCreate(
            [
                'eventversion_id' => $eventversion->id,
                'eventensemble_id' => $eventensemble->id,
            ],
            [
                'locked' => ($current->locked()) ? 0 : 1,
                'user_id' => auth()->id(),
            ]
        );

        return redirect(route('eventadministrator.tabroom.cutoffs',[
            'eventversion' => $eventversion,
        ]));
    }
}
