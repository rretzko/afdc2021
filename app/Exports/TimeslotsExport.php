<?php

namespace App\Exports;

use App\Models\School;
use App\Models\Timeslot;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimeslotsExport implements FromCollection, WithHeadings, WithMapping
{
    private $eventversion;
    private $instrumentations;
    private $timeslots;

    public function __construct(\App\Models\Eventversion $eventversion)
    {
        $this->eventversion = $eventversion;
        $this->instrumentations = $this->eventversion->instrumentations();
        $this->timeslots = Timeslot::where('eventversion_id', $this->eventversion->id)
            ->orderBy('armytime')
            ->get();
    }

    /**
    * @return Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->timeslots;
    }

    public function headings(): array
    {
        $headings =  ['timeslot','school'];

        foreach($this->instrumentations AS $instrumentation){
            $headings[] = strtoupper($instrumentation->abbr);
        }

        $headings[] = 'total';

        return $headings;
    }

    public function map($timeslot): array
    {
        $rowtotal = 0;
        $school = School::find($timeslot->school_id);

        $a = [
            $timeslot->timeslot,
            $school->shortName,
        ];

        foreach($this->instrumentations AS $instrumentation){
            $count = $this->eventversion->registrantsForSchoolByInstrumentation($school, $instrumentation)->count() ?: '0';
            $a[] = $count;
            $rowtotal += $count;
        }

        $a[] = $rowtotal;

        return $a;
    }
}
