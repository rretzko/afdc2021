<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventversionconfig extends Model
{
    use HasFactory;

    protected $primaryKey = 'eventversion_id';

    protected $fillable = ['alternating_scores','audiofiles','bestscore','eapplication','epaymentsurcharge',
    'eventversion_id','grades','judge_count','instrumentation_count','max_count','max_uppervoice_count','membershipcard',
    'missing_judge_average','onsiteregistrationfee','paypalstudent','paypalteacher','registrationfee','videofiles',
    'virtualaudition'];

    public function eventversion()
    {
        return $this->belongsTo(Eventversion::class);
    }
}
