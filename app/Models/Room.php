<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
