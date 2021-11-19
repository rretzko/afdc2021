<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TimeslotsComponent extends Component
{
    public $eventversion;
    public $interval;
    public $timeslot = 0;

    public function mount()
    {
        $this->interval = 15; //default to 15 minute interval
    }

    public function render()
    {
        return view('livewire.timeslots-component',[
            'eventversion' => $this->eventversion,
            'schools' => $this->eventversion->participatingSchools,
            'timeslots' => $this->timeslots()
        ]);
    }

    public function newTime($value)
    {
        dd($value);
    }

    public function updateTimeslot($value)
    {
        dd($value);
    }

    private function timeslots()
    {
        $timeslot = new \App\Models\Timeslot;

        return $timeslot->buildTimeslots($this->interval);
    }
}
