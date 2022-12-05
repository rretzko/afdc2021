<?php

namespace App\Models\Utility;

use App\Models\Eventversion;
use App\Models\PaymentCategory;
use App\Models\Paymenttype;
use App\Models\Registranttype;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcceptedParticipants extends Model
{
    use HasFactory;

    static private $not_accepteds = ['inc','na','n/a','pending'];
    static public $sum_balance_due = 0;
    static public $sum_paypal_students = 0;
    static public $sum_paypal_teachers = 0;
    static public $count_total = 0;

    static public function countsBySchoolWithPayPalPayments(Eventversion $eventversion, Collection $schools): array
    {
        $a = [];
        $eventversion_id = $eventversion->id;
        $fee = $eventversion->eventversionconfig->participation_fee_amount;

        foreach($schools AS $school){

            $count = self::acceptedStudentsCountBySchool($eventversion_id, $school->id);
            $paypal_students = self::acceptedStudentsTotalPayPalPaymentsBySchool($eventversion_id, $school->id);
            $paypal_teacher = self::acceptedStudentsTotalTeacherPayPalPaymentsBySchool($eventversion_id, $school->id);
            $balance_due = (($count * $fee) - $paypal_students - $paypal_teacher);
            self::$count_total += $count;
            self::$sum_balance_due += $balance_due;
            self::$sum_paypal_students += $paypal_students;
            self::$sum_paypal_teachers += $paypal_teacher;

            $a[] = [
                'name' => $school->name,
                'count' => $count,
                'paypal_students' => number_format($paypal_students,2),
                'paypal_teacher' => number_format($paypal_teacher, 2),
                'balance_due' => number_format($balance_due, 2),
            ];
        }

        return $a;
    }

    static private function acceptedStudentsCountBySchool(int $eventversion_id, int $school_id): int
    {
        return DB::table('registrants')
            ->join('scoresummaries', 'registrants.id','=','scoresummaries.registrant_id')
            ->join('schools','registrants.school_id','=','schools.id')
            ->where('registrants.eventversion_id', $eventversion_id)
            ->where('registrants.school_id', $school_id)
            ->where('registrants.registranttype_id', Registranttype::REGISTERED)
            ->whereNotIn('scoresummaries.result', self::$not_accepteds)
            ->count('registrants.id');
    }

    static private function acceptedStudentsTotalPayPalPaymentsBySchool(int $eventversion_id, int $school_id): int
    {
        return DB::table('registrants')
            ->join('payments','registrants.id', '=','payments.registrant_id')
            ->where('registrants.school_id', '=', $school_id)
            ->where('registrants.eventversion_id', '=', $eventversion_id)
            ->where('payments.paymentcategory_id','=', PaymentCategory::PARTICIPATION)
            ->where('payments.paymenttype_id','=', Paymenttype::PAYPAL)
            ->whereNotNull('payments.registrant_id')
            ->sum('amount');
    }

    static private function acceptedStudentsTotalTeacherPayPalPaymentsBySchool(int $eventversion_id, int $school_id): int
    {
        return DB::table('payments')
            ->where('school_id', '=', $school_id)
            ->where('eventversion_id', '=', $eventversion_id)
            ->where('payments.paymentcategory_id','=', PaymentCategory::PARTICIPATION)
            ->where('payments.paymenttype_id','=', Paymenttype::PAYPAL)
            ->whereNull('registrant_id')
            ->sum('amount');
    }
}
