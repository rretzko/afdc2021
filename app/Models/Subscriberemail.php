<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriberemail extends Model
{
    use HasFactory,Encryptable;

    protected $encryptable = ['email'];

    public function person()
    {
        return $this->hasOne(User::class);
    }
}
