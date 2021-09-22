<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Membership extends Model
{
    use HasFactory;

    /**
     * Return collection of organizations where $user_id is a member
     * @param $user_id
     * @return \Illuminate\Support\Collection
     */
    public function hasMember($user_id) : Collection
    {
        $organizations = collect();

        foreach($this->where('user_id', $user_id)->get() AS $membership){

            $organizations->push(Organization::find($membership->organization_id));
        }

        return $organizations;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
