<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['descr', 'eventversion_id', 'order_by','tolerance'];

    public function adjudicators()
    {
        return $this->hasMany(Adjudicator::class);
    }

    public function filecontenttypes()
    {
        return $this->belongsToMany(Filecontenttype::class)
            ->orderBy('order_by');
    }

    public function instrumentations()
    {
        return $this->belongsToMany(Instrumentation::class);
    }

    public function auditionees()
    {
        $instrumentations = $this->instrumentations;

        $r = Registrant::where('eventversion_id', Userconfig::getValue('eventversion', auth()->id()))
            ->where('registranttype_id', Registranttype::REGISTERED)
            ->get();

        $a = $r->filter(function($registrant) use($instrumentations){
                return $instrumentations->contains($registrant->instrumentations->first());
            });

        return $a;
    }

    public function auditioneesCount()
    {
        $instrumentationids = $this->instrumentations->pluck('id')->toArray();

        return Registrant::with('instrumentations')
            ->join('instrumentation_registrant','instrumentation_registrant.registrant_id','=','registrants.id')
            ->whereIn('instrumentation_registrant.instrumentation_id',  $instrumentationids)
            ->where('registrants.eventversion_id', $this->eventversion_id)
            ->where('registrants.registranttype_id', Registranttype::REGISTERED)
            ->count('registrants.id');
    }

    public function auditioneesScoredCountByAdjudicator($adjudicator)
    {
        $instrumentationids = $this->instrumentations->pluck('id')->toArray();

        return Registrant::with('instrumentations')
            ->join('instrumentation_registrant','instrumentation_registrant.registrant_id','=','registrants.id')
            ->join('scores', 'scores.registrant_id', '=', 'registrants.id')
            ->whereIn('instrumentation_registrant.instrumentation_id',  $instrumentationids)
            ->where('registrants.eventversion_id', $this->eventversion_id)
            ->where('scores.user_id', '=', $adjudicator->user_id)
            ->distinct('registrants.id')
            ->count('registrants.id');
    }
}
