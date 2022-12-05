<?php

namespace App\Models\Utility;

use App\Models\Registranttype;
use App\Models\School;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcceptedSchools extends Model
{
    use HasFactory;

    /**
     * Return collection of schools accepted into $eventversion_id ensembles
     * @param int $eventversion_id
     * @return Collection
     */
    static public function byEventversion(int $eventversion_id): Collection
    {
        $not_accepteds = ['inc','na','n/a','pending'];

        $school_ids = DB::table('registrants')
            ->join('scoresummaries', 'registrants.id','=','scoresummaries.registrant_id')
            ->join('schools','registrants.school_id','=','schools.id')
            ->where('registrants.eventversion_id', $eventversion_id)
            ->where('registrants.registranttype_id', Registranttype::REGISTERED)
            ->whereNotIn('scoresummaries.result', $not_accepteds)
            ->distinct('registrants.school_id')
            ->pluck('registrants.school_id');

        return School::find($school_ids)->sortBy('name');
    }
}
