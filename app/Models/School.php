<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class School extends Model
{
    use HasFactory;

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    /**
     * @since 2020.05.28
     *
     * abbreviate common terms
     *
     * @return string
     */
    public function getShortNameAttribute() : string
    {
        $abbrs = [
            'High School' => 'HS',
            'Regional High School' => 'RHS',
            'International' => 'Int\'l',
            'University' => 'U',
        ];

        $haystack = $this->name; //avoid repeated downstream calls
        $str = $haystack;   //initialize $str value

        foreach($abbrs AS $descr => $abbr){

            if(strstr($haystack, $descr)){

                $str = str_replace($descr, $abbr, $haystack);
            }
        }

        return $str;
    }

    public function getTeachersAttribute()
    {
        return Teacher::with(['person' => function($query){
            $query->orderBy('last');
            }])
            ->whereIn('user_id', $this->users->modelKeys())
            ->get();

    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
