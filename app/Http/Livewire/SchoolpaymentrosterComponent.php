<?php

namespace App\Http\Livewire;

use App\Models\Schoolpayment;
use App\Models\Userconfig;
use Livewire\Component;

class SchoolpaymentrosterComponent extends Component
{
    public $eventversion;
    protected $listeners = ['refreshcheckregister' => 'render'];

    public function render()
    {
        return view('livewire.schoolpaymentroster-component',
        [
            'payments' => $this->schoolPayments(),
        ]);
    }

    private function schoolPayments()
    {
        return Schoolpayment::with('person','school')
            ->where('eventversion_id', $this->eventversion->id)
            ->orderByDesc('id')
            ->get();
    }
}
