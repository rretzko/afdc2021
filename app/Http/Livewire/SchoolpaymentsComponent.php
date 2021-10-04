<?php

namespace App\Http\Livewire;

use App\Models\Eventversion;
use App\Models\School;
use App\Models\Schoolpayment;
use App\Models\Userconfig;
use Livewire\Component;

class SchoolpaymentsComponent extends Component
{
    public $amount = 0;
    public $comments = '';
    public $eventversion;
    public $schoolid;
    public $targetschool;
    public $teachers;
    public $userid = 0;

    protected $rules = [
        'targetschool' => ['required'],
    ];

    public function mount()
    {
        $this->schoolid = 0;
        $this->teachers = NULL;
    }

    public function render()
    {
        if(! $this->eventversion) {
            $this->eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        }

        return view('livewire.schoolpayments-component',
        [
            'schools' => $this->eventversion->participatingSchools,
            'teachers' => $this->teachers,
        ]);
    }

    public function updatedTargetschool($value)
    {
        $this->targetschool = School::find($value);

        $this->teachers = $this->targetschool->teachers;
        $this->userid = $this->teachers->first()->user_id;
    }

    public function updateSchoolpayment()
    {
        $this->validate([
            'amount' => ['required','numeric'],
            'comments' => ['nullable','string'],
        ]);

        Schoolpayment::create(
            [
                'eventversion_id' => $this->eventversion->id,
                'school_id' => $this->targetschool->id,
                'user_id' => $this->userid,
                'amount' => $this->amount,
                'comments' => $this->comments,
                'updated_by' => auth()->id(),
            ],
        );

        $this->emit('refreshcheckregister');

        $this->reset();
    }
}
