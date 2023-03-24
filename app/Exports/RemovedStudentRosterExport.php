<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RemovedStudentRosterExport implements FromCollection, WithHeadings, WithMapping
{
    public $removeds;

    public function __construct(Collection $removeds)
    {
        $this->removeds = $removeds;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->removeds;
    }

    public function headings(): array
    {
        return [
            'student',
            'school',
            'voice'
        ];
    }

    public function map($row): array
    {
        return [
            $row->fullNameAlpha,
            $row->schoolName,
            $row->instrumentations->first()->formattedDescr(),
        ];
    }
}
