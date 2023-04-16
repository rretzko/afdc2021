<?php

namespace App\Exports;

use App\Models\Person;
use App\Models\School;
use App\Models\Schoolpayment;
use App\Models\Userconfig;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SchoolPaymentsExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Schoolpayment::where('eventversion_id', Userconfig::getValue('eventversion', auth()->id()))
            ->get();
    }

    public function headings(): array
    {
        return [
            'school_id',
            'user_id',
            'amount',
            'comments',
            'created_by',
            'created_at',
        ];
    }

    public function map($row): array
    {
        return [
            School::find($row->school_id)->name,
            Person::find($row->user_id)->fullNameAlpha(),
            $row->amount,
            $row->comments,
            Person::find($row->updated_by)->fullNameAlpha(),
            $row->created_at
        ];
    }
}
