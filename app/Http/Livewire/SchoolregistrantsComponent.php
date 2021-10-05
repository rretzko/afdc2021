<?php

namespace App\Http\Livewire;

use App\Models\Adminreview;
use App\Models\Eventversion;
use App\Models\School;
use App\Models\Userconfig;
use Livewire\Component;

class SchoolregistrantsComponent extends Component
{
    public $eventversion;
    public $reviews = [];
    public $school;

    public function mount(School $school)
    {
        $this->eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $this->reviews = $this->getReviews();
        $this->school = $school;
    }
    public function render()
    {
        return view('livewire.schoolregistrants-component', [
            'registrants' => $this->eventversion->registrantsForSchool($this->school),
        ]);
    }

    public function updateReviewed($registrant_id)
    {
        $adminreview = Adminreview::where('registrant_id', $registrant_id)->where('user_id', auth()->id())->first();

        $curreview = ($adminreview) ? $adminreview->reviewed : 0;

        Adminreview::updateOrCreate(
            [
                'registrant_id' => $registrant_id,
                'user_id' => auth()->id(),
                'eventversion_id' => $this->eventversion->id,
            ],
            [
                'reviewed' => $curreview ? 0 : 1,
            ]
        );
    }

    private function getReviews()
    {
        return Adminreview::where('eventversion_id', $this->eventversion->id)
            ->where('reviewed', 1)
            ->pluck('registrant_id')
            ->toArray();
    }
}
