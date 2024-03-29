<?php

namespace App\Models;

use App\Traits\SenioryearTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Student extends Model
{
    use HasFactory, SenioryearTrait;

    protected $primaryKey = 'user_id';

    public function getCurrentSchoolAttribute()
    {;
        if($this->user_id) {//check for teachers who have not added the first student
            foreach ($this->person->user->schools as $school) {

                if ($school->grades &&
                    in_array($this->getGradeAttribute(), $school->grades)) {

                    return $school;
                }
            }

            Log::info('*** FJR: '.__METHOD__.': user_id: '.$this->user_id.': school_id: '.$school->id.': grades: '.serialize($school->grades).': gradeAttribute: '.$this->getGradeAttribute());
            Log::info('*** FJR: Check the student grade v. grades @ the school v. grades teacher has checked.');
        }
        return new School;
    }

    public function getCurrentTeacherAttribute()
    {
        if($this->teachers->count() === 1){ return $this->teachers->first(); }

        foreach($this->teachers AS $teacher){

            if($this->getCurrentSchoolAttribute()->teachers->contains($teacher)){

                return $teacher;
            }
        }
    }

    public function getGradeAttribute()
    {
        $sr_year = $this->senioryear();

        //early exit
        if($this->classof < $sr_year){ return 'alum';}

        return (12 - ($this->classof - $sr_year));
    }

    public function getEmailPersonalAttribute()
    {
        return Nonsubscriberemail::where('user_id',$this->user_id)
                ->where('emailtype_id', Emailtype::where('descr', 'email_student_personal')->first()->id)
                ->first()
            ?? new Nonsubscriberemail;
    }

    public function getEmailSchoolAttribute()
    {
        return Nonsubscriberemail::where('user_id',$this->user_id)
                ->where('emailtype_id', Emailtype::where('descr', 'email_student_school')->first()->id)
                ->first()
            ?? new Nonsubscriberemail;
    }

    public function getEmailsAttribute()
    {
        $emails = [];

        if($this->getEmailPersonalAttribute()->id){
            $emails[] = $this->getEmailPersonalAttribute();
        }

        if($this->getEmailSchoolAttribute()->id){
            $emails[] = $this->getEmailSchoolAttribute();
        }

        return collect($emails);
    }

    public function getEmailsCsvAttribute()
    {
        $emails = [];

        if($this->getEmailPersonalAttribute()->id){
            $emails[] = $this->getEmailPersonalAttribute()->email;
        }

        if($this->getEmailSchoolAttribute()->id){
            $emails[] = $this->getEmailSchoolAttribute()->email;
        }

        return implode(', ',$emails);
    }

    public function getHeightFootInchAttribute()
    {
        return floor($this->height / 12)."' ".($this->height % 12).'" ('.$this->height.'")';
    }

    public function getPhonesCsvAttribute()
    {
        $phones = [];

        if($this->getPhoneHomeAttribute()->id){
            $phones[] = $this->getPhoneHomeAttribute()->labeledPhone;
        }

        if($this->getPhoneMobileAttribute()->id){
            $phones[] = $this->getPhoneMobileAttribute()->labeledPhone;
        }

        return implode(', ',$phones);
    }

    public function getPhoneHomeAttribute()
    {
        return Phone::where('user_id',$this->user_id)
                ->where('phonetype_id', Phonetype::STUDENT_HOME)
                ->first()
            ?? new Phone;
    }

    public function getPhoneMobileAttribute()
    {
        return Phone::where('user_id',$this->user_id)
                ->where('phonetype_id', Phonetype::STUDENT_MOBILE)
                ->first()
            ?? new Phone;
    }

    public function getShirtsizeDescrAttribute()
    {
        return Shirtsize::where('id', $this->shirtsize_id)->first()->descr;
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'guardian_student', 'student_user_id', 'guardian_user_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'user_id', 'user_id');
    }

    public function registrants()
    {
        return $this->hasMany(Registrant::class, 'user_id', 'user_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'student_teacher','student_user_id', 'teacher_user_id')
            ->withPivot('studenttype_id')
            ->withTimestamps()
            ->orderBy('updated_at','desc');
    }
}
