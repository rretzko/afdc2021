<?php

namespace App\Exports;

use App\Models\Eventversion;
use App\Models\Obligation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AckowledgedTeachersExport implements FromCollection, WithHeadings,WithMapping
{
    private $obligations;

    public function __construct(\App\Models\Eventversion $eventversion)
    {
        $this->obligations = $this->getObligations($eventversion);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->obligations;
    }

    public function headings(): array
    {
        return [
            'user_id',
            'last',
            'first',
            'middle',
            'school',
            'email_work',
            'email_home',
            'email_other',
            'phone_cell',
            'phone-work',
        ];
    }

    public function map($obligation): array
    {
        return [
            $obligation['person']->user_id,
            $obligation['person']->last,
            $obligation['person']->first,
            $obligation['person']->middle,
            $obligation['teacher']->currentSchool()->name,
            $obligation['person']->subscriberEmailWork,
            $obligation['person']->subscriberEmailPersonal,
            $obligation['person']->subscriberEmailOther,
            $obligation['person']->phoneMobile,
            $obligation['person']->phoneWork,
        ];
    }

    private function getObligations(Eventversion $eventversion)
    {
        return Obligation::with('person','teacher')
            ->where('eventversion_id', $eventversion->id)
            ->where('acknowledgment',1)
            ->get()
            ->sortBy(['person.last','person.first']);
    }


}
