<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    public function getSubscriberemailworkAttribute()
    {
        return $this->subscriberemails->where('emailtype_id', Emailtype::WORK)->first()->email ?? '';
    }

    public function fullname()
    {
        $str = $this->first;

        if(strlen($this->middle)){

            $str .= ' '.$this->middle;
        }

        $str .= ' '.$this->last;

        return $str;
    }

    public function fullnameAlpha()
    {
        $str = $this->last.', '.$this->first;

        if(strlen($this->middle)){

            $str .= ' '.$this->middle;
        }

        return $str;
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'user_id');
    }

    public function subscriberemails()
    {
        return $this->hasMany(Subscriberemail::class, 'user_id');
    }

    public function phones()
    {
        return $this->hasMany(Phone::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
