<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use HasFactory;

    public function sumByEventversion(Eventversion $eventversion, array $counties)
    {
        $amount = DB::select(DB::raw("
            SELECT SUM(payments.amount) AS sum FROM payments,schools
            WHERE payments.eventversion_id= :eventversion_id
            AND payments.school_id=schools.id
            AND schools.county_id IN (".implode(',',$counties).")
        "),
            ['eventversion_id' => $eventversion->id]
        );

        return ($amount[0]->sum);

    }

    public function sumBySchool(Eventversion $eventversion, School $school)
    {
        return $this->where('eventversion_id', $eventversion->id)
            ->where('school_id', $school->id)
            ->sum('amount');
    }
}
