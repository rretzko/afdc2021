<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrant extends Model
{
    use HasFactory;

    protected $fillable = ['eventversion_id', 'id', 'programname', 'registranttype_id', 'school_id', 'user_id'];

    public function application()
    {
        return $this->hasOne(Application::class,'id', 'registrant_id');
    }

    public function instrumentations()
    {
        return $this->belongsToMany(Instrumentation::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }

}
