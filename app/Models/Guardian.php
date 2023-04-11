<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];
    protected $primaryKey = 'user_id';

    public function getEmailAlternateAttribute()
    {
        return Nonsubscriberemail::where('user_id', $this->user_id)
            ->where('emailtype_id', Emailtype::GUARDIAN_ALTERNATE)
            ->first() ?? new Nonsubscriberemail;
    }

    /**
     * @return string
     */
    public function getEmailsCsvAttribute()
    {
        $emails = [];
        $alternate = $this->getEmailAlternateAttribute();
        $primary = $this->getEmailPrimaryAttribute();

        if($primary->id){ $emails[] = $primary->email;}
        if($alternate->id){ $emails[] = $alternate->email;}

        return implode(', ',$emails);return $this->getEmailCsvAttribute();
    }

    /**
     * @deprecated 27-Nov-21
     * synonym to newer getEmailsCsvAttribute
     * @return string
     */
    public function getEmailCsvAttribute()
    {
        return $this->getEmailsCsvAttribute();
    }

    public function getEmailPrimaryAttribute()
    {
        return Nonsubscriberemail::where('user_id', $this->user_id)
                ->where('emailtype_id', Emailtype::GUARDIAN_PRIMARY)
                ->first() ?? new Nonsubscriberemail;
    }

    public function getFullNameAlphaAttribute()
    {
        return $this->person->fullNameAlpha();
    }
    /**
     * @since 2021-11-30
     * @return string
     */
    public function getPhonesCsvAttribute()
    {
        $phones = [];
        $home = $this->getPhoneHomeAttribute();
        $mobile = $this->getPhoneMobileAttribute();
        $work = $this->getPhoneWorkAttribute();

        if($mobile->id && strlen($mobile->phone)){ $phones[] = $mobile->phone . ' (pc)';}
        if($home->id && strlen($home->phone)){ $phones[] = $home->phone.' (ph)';}
        if($work->id && strlen($work->phone)){ $phones[] = $work->phone.' (pw)';}

        return implode(', ',$phones);
    }

    /**
     * @deprecated 2021-11-30
     * @return string
     */
    public function getPhoneCsvAttribute()
    {
        return $this->getPhonesCsvAttribute();
    }

    public function getPhoneHomeAttribute()
    {
        return $this->getPhone('phone_guardian_home');
    }

    public function getPhoneMobileAttribute()
    {
        return $this->getPhone('phone_guardian_mobile');
    }

    public function getPhoneWorkAttribute()
    {
        return $this->getPhone('phone_guardian_work');
    }

    public function guardiantype($student_user_id)
    {
        $id = DB::table('guardian_student')
            ->select('guardiantype_id')
            ->where('guardian_user_id', '=', $this->user_id)
            ->where('student_user_id', '=', $student_user_id)
            ->value('guardiantype_id');

        return Guardiantype::find($id);
    }

    public function setSearchables()
    {
        $user = $this->person->user;

        $this->updateSearchables($user, 'name', $this->person->first.$this->person->middle.$this->person->last);
        $this->updateSearchables($user, 'email_guardian_alternate', $this->emailAlternate->email);
        $this->updateSearchables($user, 'email_guardian_primary', $this->emailPrimary->email);
        $this->updateSearchables($user, 'phone_guardian_home', $this->phoneHome);
        $this->updateSearchables($user, 'phone_guardian_mobile', $this->phoneMobile);
        $this->updateSearchables($user, 'phone_guardian_work', $this->phoneWork);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'guardian_student', 'guardian_user_id', 'student_user_id')
            ->withPivot('guardiantype_id');
    }

    public function person()
    {
        return $this->hasOne(Person::class, 'user_id', 'user_id');
    }

/** END OF PUBLIC FUNCTIONS  *************************************************/

    private function formatPhone($phone): string
    {
        //if(strpos($phone, '(')){

//            return $phone;
  //      }

        //return '(' . substr($phone, 0, 3) . ') '; //.substr($phone, 4, 3) . '-' . substr($phone, 7);
        return $phone;
    }

    private function getEmail($emailtype_descr)
    {
        return Nonsubscriberemail::find(
            DB::table('nonsubscriberemails')
                ->select('id')
                ->where('user_id', $this->user_id)
                ->where('emailtype_id', Emailtype::where('descr', $emailtype_descr)->first()->id)
                ->value('id')
            ?? 0
        )
            ?? new Email;
    }

    private function getPhone($phonetype_descr)
    {
        return Phone::find(
                DB::table('phones')
                    ->select('id')
                    ->where('user_id', $this->user_id)
                    ->where('phonetype_id', Phonetype::where('descr', $phonetype_descr)->first()->id)
                    ->value('id')
                ?? 0
            )
            ?? new Phone;
    }
}
