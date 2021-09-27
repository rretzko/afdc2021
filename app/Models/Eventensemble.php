<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventensemble extends Model
{
    use HasFactory;

    public function instrumentations()
    {
        $eventensembletype = Eventensembletype::find($this->eventensembletype_id);

        return $eventensembletype->instrumentations()->get();
    }
}
