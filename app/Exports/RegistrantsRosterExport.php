<?php

namespace App\Exports;

use App\Models\Emailtype;
use App\Models\Instrumentation;
use App\Models\Nonsubscriberemail;
use App\Models\Phone;
use App\Models\Phonetype;
use App\Models\Timeslot;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrantsRosterExport implements FromCollection, WithHeadings, WithMapping
{
    private $eventversion_id;
    private $registrants;
    private $timeslot;

    public function __construct(\App\Models\Eventversion $eventversion)
    {
        $this->eventversion_id = Userconfig::getValue('eventversion', auth()->id());

        $ra = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        $this->registrants= $ra->registrantsByTimeslotSchoolNameFullnameAlpha(new Instrumentation);

        $this->timeslot = new Timeslot;
    }

    public function collection()
    {
        return $this->registrants;
    }

    public function headings(): array
    {
        return [
            '###',
            'timeslot',
            'Reg.Id.',
            'Name',
            'School',
            'Voice Part',
        ];
    }

    public function map($registrant): array
    {
        static $cntr = 1;

        $a = [
            $cntr,
            $this->timeslot->timeslot($this->eventversion_id, $registrant->school_id),
            $registrant->id,
            $registrant->student->person->fullNameAlpha(),
            $registrant->schoolname,
            $registrant->instrumentations()->first()->formattedDescr(),
        ];

        $cntr++;

        return $a;
    }
}
