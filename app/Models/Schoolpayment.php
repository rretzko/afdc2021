<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schoolpayment extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'comments', 'eventversion_id','school_id','updated_by', 'user_id'];

    public function school()
    {
        return $this->hasOne(School::class, 'id', 'school_id');
    }

    /**
     * Refers to teacher-payer
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function person()
    {
        return $this->hasOne(Person::class, 'user_id', 'user_id');
    }

    public function updatedByFullnameAlpha()
    {
        $person = Person::find($this->updated_by);

        return $person->fullnameAlpha();
    }
}
