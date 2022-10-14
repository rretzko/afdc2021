<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    public function signatureCount(Registrant $registrant): int
    {
        return Signature::where('registrant_id', $registrant->id)
            ->whereNotNull('confirmed_by')
            ->count() ?? 0;
    }
}
