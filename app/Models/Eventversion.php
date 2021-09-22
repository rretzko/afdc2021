<?php

namespace App\Models;

use App\Models\Eventversionconfig;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Eventversion extends Model
{
    use HasFactory;

    public function countInvitations()
    {
        return 15;
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventversionconfig()
    {
        return $this->hasOne(Eventversionconfig::class);
    }

    /**
     * Scope a query to only include schools in Eventversion grades.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function schools()
    {
        //ex.[9,10,11,12]
        $grades = explode(',', $this->eventversionconfig->grades);
        $memberships = Membership::where('organization_id', $this->event->organization->id)->get();

        $raw = DB::table('gradetype_school_user')
            ->whereIn('gradetype_id', $grades)
            ->distinct()
            ->get()
            ->pluck('school_id')
            ->toArray();

        $schoolswitheventversiongrades = array_unique($raw);

        $schools = [];

        foreach($memberships AS $membership){

            foreach($membership->user->schools AS $school){

                if(in_array($school->id, $schoolswitheventversiongrades)){

                    $schools[$school->id] = $school;
                }
            }
        }

        $collection = collect($schools);

        return ($collection->sortBy('name'));
    }

    public function schoolsByCounties(array $counties)
    {
        $schools = collect();

        foreach($this->schools() AS $school){

            if(in_array($school->county_id, $counties)){

                $schools->push($school);
            }
        };

        return $schools;
    }
}
