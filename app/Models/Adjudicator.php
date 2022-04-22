<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Adjudicator extends Model
{
    use HasFactory;

    protected $fillable = ['adjudicatorstatustype_id', 'eventversion_id', 'rank', 'room_id', 'user_id'];

    public function adjudicatorsByRegistrantid(int $registrant_id)
    {
        return DB::table('scores')
            ->where('registrant_id', $registrant_id)
            ->distinct()
            ->get('user_id')
            ->toArray();
    }

    public function getAdjudicatornameAttribute()
    {
        return User::find($this->user_id)->person->fullnameAlpha();
    }

    public function person()
    {
        return $this->belongsTo(Person::class,'user_id','user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }


}
