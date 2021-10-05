<?php

namespace App\Models;

use App\Models\Utility\Fileviewport;
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

    public function fileuploads()
    {
        $collect = collect();
        $eventversion = Eventversion::find($this->eventversion_id);

        foreach($eventversion->filecontenttypes AS $filecontenttype){

            $collect->push(Fileupload::where('registrant_id', $this->id)
                ->where('filecontenttype_id', $filecontenttype->id)
                ->first());
        }

        return $collect;
    }

    /**
     * Return the embed code for the requested videotype
     *
     * NOTE: self::hasVideoType($videotype) should be run BEFORE this function.
     *
     * @param Videotype $videotype
     * @return string
     */
    public function fileviewport(Filecontenttype $filecontenttype)
    {
        $viewport = new Fileviewport($this,$filecontenttype);

        return $viewport->viewport();
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
