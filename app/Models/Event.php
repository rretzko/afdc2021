<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['auditioncount','first_event','frequency','grades','logo_file','logo_file_alt','name',
        'organization_id','requiredheight', 'requiredshirtsize', 'short_name','status'];

    public function currentEventversion()
    {
        $eventversion_id = Userconfig::getValue('eventversion', auth()->id());

        return ($eventversion_id) ? Eventversion::find($eventversion_id) : new Eventversion;
    }

    public function eventensembles()
    {
        return $this->hasMany(Eventensemble::class);
    }

    public function eventversions()
    {
        return $this->hasMany(Eventversion::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


}
