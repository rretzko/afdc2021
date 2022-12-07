<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaypalReconciliationExport implements FromCollection, WithHeadings, WithMapping
{
    public $payments;

    public function __construct(Collection $payments)
    {
        $this->payments = $payments;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
          'participant',
          'registrant_id',
          'school',
          'amount',
          'process_date',
        ];
    }

    public function map($row): array
    {
        return [
          $row['person']->fullnameAlpha(),
          $row->registrant_id,
          $row['school']->name,
          $row->amount,
          $row->updated_at,
        ];
    }
}
