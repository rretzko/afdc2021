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

        return Carbon::parse($this->dt)->format('Y-m-d');
    }

    public function getDtYMDHMAAttribute(): string
    {
        //early exit
        if(is_null($this->dt)){ return date('Y-M-d g:i a', strtotime('NOW')); }

        return Carbon::parse($this->dt)->format('Y-m-d H:i');
    }
}
