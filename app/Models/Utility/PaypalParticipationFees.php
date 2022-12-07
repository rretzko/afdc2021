<?php

namespace App\Models\Utility;

use App\Models\Payment;
use App\Models\PaymentCategory;
use App\Models\Paymenttype;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalParticipationFees extends Model
{
    use HasFactory;

    static public function byEventversion(int $eventversion_id): Collection
    {
        return Payment::with('person','school')
            ->where('eventversion_id', $eventversion_id)
            ->where('paymentcategory_id', PaymentCategory::PARTICIPATION)
            ->where('paymenttype_id', Paymenttype::PAYPAL)
            ->get()
            ->sortBy(['school.name','person.last']);
    }
}
