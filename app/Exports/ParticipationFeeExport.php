<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipationFeeExport implements FromArray, WithHeadings, WithMapping
{
    public $acceptances;

    public function __construct(array $acceptances)
    {
        $this->acceptances = $acceptances;
    }

    public function array(): array
    {
        return $this->acceptances;
    }

    public function headings(): array
    {
        return [
            'school',
            'students',
            'paypal students',
            'paypal teacher',
            'balance due'
        ];
    }

    public function map($row): array
    {
        return [
            $row['name'],
            $row['count'],
            $row['paypal_students'],
            $row['paypal_teacher'],
            $row['balance_due'],
        ];
    }
}
