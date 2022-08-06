<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Eventversiondate extends Model
{
    use HasFactory;

    protected $fillable = ['datetype_id', 'dt','eventversion_id'];

    public function getDtYMDAttribute(): string
    {
        //early exit
        if(is_null($this->dt)){ return date('Y-M-d', strtotime('NOW')); }

        return substr($this->dt,0,4).'-'
            .substr($this->dt,5,2).'-'
            .substr($this->dt,8,2);
    }
}
