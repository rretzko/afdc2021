<?php

namespace App\Http\Livewire;

use App\Models\Utility\TableTimeslots;
use Livewire\Component;

class TimeslotsComponent extends Component
{
    public $csrf;
    public $eventversion;
    public $interval;
    public $route;
    public $timeslot = 0;

    public function mount()
    {
        $this->interval = 15; //default to 15 minute interval
    }

    public function render()
    {
        return view('livewire.timeslots-component',[
            'eventversion' => $this->eventversion,
            'table' => $this->tableTimeslots()
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

    private function tableTimeslots()
    {
        $table = new TableTimeslots([
            'csrf' => $this->csrf,
            'eventversion' => $this->eventversion,
            'route' => $this->route,
            'schools' => $this->eventversion->participatingSchools
        ]);

        return $table->table();
    }

    private function timeslots()
    {
        $timeslot = new \App\Models\Timeslot;

        return $timeslot->buildTimeslots($this->interval);
    }
}
