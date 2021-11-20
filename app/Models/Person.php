<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

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

    public function getSubscriberemailotherAttribute()
    {
        return $this->subscriberemails->where('emailtype_id', Emailtype::OTHER)->first()->email ?? '';
    }

    public function getSubscriberemailpersonalAttribute()
    {
        return $this->subscriberemails->where('emailtype_id', Emailtype::PERSONAL)->first()->email ?? '';
    }

    public function getSubscriberemailworkAttribute()
    {
        return $this->subscriberemails->where('emailtype_id', Emailtype::WORK)->first()->email ?? '';
    }

    public function getSubscriberEmailsCsvAttribute()
    {
        $emails = [];

        $personal = Subscriberemail::where('user_id', $this->user_id)
            ->where('emailtype_id', Emailtype::PERSONAL)
            ->first() ?? NULL;

        $work = Subscriberemail::where('user_id', $this->user_id)
            ->where('emailtype_id', Emailtype::WORK)
            ->first() ?? NULL;

        $other = Subscriberemail::where('user_id', $this->user_id)
            ->where('emailtype_id', Emailtype::OTHER)
            ->first() ?? NULL;

        if($personal){ $emails[] = $personal->email;}
        if($work){ $emails[] = $work->email;}
        if($other){ $emails[] = $other->email;}

        return implode(', ', $emails);
    }

    public function getPhonehomeAttribute()
    {
        return Phone::where('user_id', $this->user_id)
                ->where('phonetype_id', Phonetype::HOME)->first()->phone ?? '';
    }

    public function getPhonemobileAttribute()
    {
        return Phone::where('user_id', $this->user_id)
            ->where('phonetype_id', Phonetype::MOBILE)->first()->phone ?? '';
    }

    public function getPhoneworkAttribute()
    {
        return Phone::where('user_id', $this->user_id)
                ->where('phonetype_id', Phonetype::WORK)->first()->phone ?? '';
    }

    public function getSubscriberPhoneCsvAttribute()
    {
        $phones = [];

        $home = Phone::where('user_id', $this->user_id)
            ->where('phonetype_id', Phonetype::HOME)
            ->first();

        $mobile = Phone::where('user_id', $this->user_id)
            ->where('phonetype_id', Phonetype::MOBILE)
            ->first();

        $work = Phone::where('user_id', $this->user_id)
            ->where('phonetype_id', Phonetype::WORK)
            ->first();

        if($mobile){$phones[] = $mobile->phone.' (c)';}
        if($home){$phones[] = $home->phone.' (h)';}
        if($work){$phones[] = $work->phone.' (w)';}

        return implode(', ', $phones);
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
