<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventensemblecutofflock extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id','eventensemble_id', 'instrumentation_id', 'locked', 'user_id'];

    public function locked()
    {
        return ($this->id ? $this->locked : 0);
    }
}
