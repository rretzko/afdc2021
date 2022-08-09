<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['expiration', 'grade_levels', 'membership_card_path', 'membership_id', 'membershiptype_id',
        'organization_id', 'requestedtype_id', 'subjects', 'user_id'];

    public function getExpirationMDYFullAttribute(): string
    {
        return Carbon::parse($this->expiration)->format('M d, Y');
    }

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
