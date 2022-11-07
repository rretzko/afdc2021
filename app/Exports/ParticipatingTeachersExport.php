<?php

namespace App\Exports;

use App\Models\Eventversion;
use App\Models\Obligation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipatingTeachersExport implements FromCollection, WithMapping, WithHeadings
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
            //'user_id',
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
            //$obligation['person']->user_id,
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
        $teachers = (($eventversion->event->id === 11) || ($eventversion->event->id === 12) //sjcda
            || ($eventversion->event->id === 19) //nj all-shore
            || ($eventversion->event->id === 25)) //morris area
            ? $eventversion->participatingTeachersEsignature
            : $eventversion->participatingTeachers;

        $collection = collect();
        foreach($teachers AS $teacher){
            $collection->push(Obligation::with('person','teacher')
            ->where('user_id', $teacher->user_id)
            ->where('eventversion_id', $eventversion->id)
            ->where('acknowledgment',1)
            ->first());
        }

        return $collection;
    }


}
