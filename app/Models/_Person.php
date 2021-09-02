<?php

namespace App\Models;

use App\Traits\FormatPhoneTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Person extends Model
{
    use FormatPhoneTrait;
    use SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'first',
        'middle',
        'last',
        'pronoun_id',
        'honorific_id',
    ];

    public $incrementing = false;


    public function address()
    {
        return $this->hasOne(Address::class, 'user_id', 'user_id');
    }

    /**
     * this function is valid for students and parents
     * (@see StudentFolder.info) but Teachers requires a one-to-one
     * person->email relationship with unique email values
     *
     * emails is included here to avoid the possibility that this construct
     * is initiated in the future
     */
    /*public function emails()
    {
        return $this->belongsToMany(Email::class, 'email_person',
                'user_id', 'email_id')
                ->withTimestamps()
                ->withPivot('type');
    }*/

    public function emails()
    {
        return $this->belongsToMany(\App\Email::class, 'email_person','user_id', 'email_id');
    }

    public function getEmailAlternateAttribute() : string
    {
        $email = self::getEmailAlternateObjectAttribute();

        return ($email->id > 0) ? $email->email : '';
    }

    public function getEmailAlternateIdAttribute() : int
    {
        $email = self::getEmailAlternateObjectAttribute();

        return $email->id ?? 0;
    }

    public function getEmailAlternateObjectAttribute() : Email
    {
        return self::email_Object('alternate') ?? new Email;
    }

    public function getEmailAlternateVerifiedAttribute() : bool
    {
        $email = self::getEmailAlternateObjectAttribute();

        return ($email->verified === "1") ?? false;
    }

    public function getEmailBlockAttribute() : string
    {
        $emails = [];

        if(strlen(self::getEmailWorkAttribute())){
            $emails[] = self::getEmailWorkAttribute();
        }

        if(strlen(self::getEmailPersonalAttribute())){
            $emails[] = self::getEmailPersonalAttribute();
        }

        //if(strlen(self::getEmailPrimaryAttribute())){
         //   $emails[] = self::getEmailPrimaryAttribute();
       // }

        //if(strlen(self::getEmailAlternateAttribute())){
        //    $emails[] = self::getEmailAlternateAttribute();
        //}

        return implode('<br />', $emails);
    }

    public function getEmailPersonalAttribute() : string
    {
        $email = self::getEmailPersonalObjectAttribute();

        return ($email->id > 0) ? $email->email : '';
    }

    public function getEmailPrimaryAttribute() : string
    {
        $email = self::getEmailPrimaryObjectAttribute();

        return ($email->id > 0) ? $email->email : '';
    }

    public function getEmailPersonalObjectAttribute() : Subscriberemail
    {
        return self::email_Object('personal') ?? new Subscriberemail;
    }

    public function getEmailPrimaryObjectAttribute() : Email
    {
        return self::email_Object('primary') ?? new Email;
    }

    public function getEmailPrimaryVerifiedAttribute() : bool
    {
        $email = self::getEmailPrimaryObjectAttribute();

        return ($email->verified === "1") ?? false;
    }

    public function getEmailStringAttribute() : string
    {
        return str_replace('<br />', ', ', $this->emailBlock);
    }

    public function getEmailWorkAttribute() : string
    {
        $email = self::getEmailWorkObjectAttribute();

        return ($email->id > 0) ? $email->email : '';
    }

    public function getEmailWorkObjectAttribute() : Subscriberemail
    {
        return self::email_Object('work') ?? new Subscriberemail;
    }

    public function getFullNameAttribute() : string
    {
        return (strlen($this->middle))
                ? $this->first.' '.$this->middle.' '.$this->last
                : $this->first.' '.$this->last;
    }

    public function getFullNameWithHonorificAttribute() : string
    {
        return self::honorific()->abbr
                . ' '
                . self::getFullNameAttribute();
    }

    public function getFullNameAlphaAttribute() : string
    {
        return strlen($this->middle)
            ? $this->last.', '.$this->first.' '.$this->middle
            : $this->last.', '.$this->first;
    }

    /**
     * Provide pronoun descr with parenthetical (male/female/non-cis) suffix
     */
    public function getGenderDescrAttribute()
    {
        $p = Pronoun::find($this->pronoun_id);

        return $p->genderProxy;
    }

    public function getOrganizationStatustypeDescrAttribute() : string
    {
        $st = \App\Statustype::find(self::getOrganizationStatustypeIdAttribute());

        return $st->descr;
    }

    public function getOrganizationStatustypeIdAttribute() : int
    {
        return DB::table('organization_person')
            ->select('statustype_id')
            ->where('organization_id', \App\Userconfig::getValue('organization'))
            ->where('user_id', $this->user_id)
            ->value('statustype_id');
    }

    public function getPhoneBlockAttribute() : string
    {
        $phones = self::build_Phone_Array();

        return implode('<br />', $phones);
    }

    public function getPhoneHomeAttribute() : string
    {
        $phone = self::getPhoneObjectAttribute('home');

        return is_null($phone->id)
            ? ''
            : self::FormatPhone($phone->phone);
    }

    public function getPhoneMobileAttribute() : string
    {
        $phone = self::getPhoneObjectAttribute('mobile');

        return is_null($phone->id)
            ? ''
            : self::FormatPhone($phone->phone);
    }

    public function getPhoneObjectAttribute($type) : Phone
    {
        return Phone::where('user_id', $this->user_id)
            ->where('phonetype_id', Phonetype::where('descr', $type)->first()->id)
            ->first() ?? new Phone;
    }

    public function getPhoneStringAttribute() : string
    {
        return str_replace('<br />', ',  ', $this->phoneBlock);
    }

    public function getPhoneWorkAttribute() : string
    {
        $phone = self::getPhoneObjectAttribute('work');

        return is_null($phone->id)
            ? ''
            : self::FormatPhone($phone->phone);
    }

    public function getPronounDescrAttribute() : string
    {
        $p = Pronoun::find($this->pronoun_id);

        return $p->descr ?? 'none found';
    }

    public function getVersionsActiveAttribute()
    {
        $a = [];

        //all authorized events
        foreach($this->getEventsAttribute() AS $event)
        {
            if(count($event->versionsActive)){


                $a = array_merge($a, $event->versionsActive);
            }
        }

        return $a;
    }

    public function honorific()
    {
        return $this->hasOne('App\Honorific', 'id', 'honorific_id');
    }

    /**
     * @todo move to Member
     * @todo redefine Member to (organization)member and create Subscriber model
     * @param Eventversion $eventversion
     * @return mixed|null
     */
    public function isInvited(\App\Eventversion $eventversion)
    {
        return DB::table('invitations')
            ->select('user_id')
            ->where('user_id','=',$this->user_id)
            ->where('eventversion_id', '=', $eventversion->id)
            ->value('user_id');
    }

    public function isTeacher() : bool
    {
        return DB::table('teachers')
            ->where('user_id', '=', $this->user_id)
            ->value('user_id') ?? false;
    }

    public function membership()
    {
        return $this->hasOne(Membership::class, 'user_id', 'user_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_person',
            'user_id', 'organization_id')
            ->withTimestamps()
            ->withPivot('authorized', 'statustype_id')
            ->orderBy('name');
    }

    public function parentguardian()
    {
        return $this->hasOne(Parentguardian::class, 'user_id');
    }

    public function phones()
    {
        return $this->belongsToMany(Phone::class, 'person_phone',
                'user_id', 'phone_id')
                ->withTimestamps()
                ->withPivot('type');
    }

    public function pronoun()
    {
        return $this->hasOne('App\Pronoun', 'id', 'pronoun_id');
    }

    public function student()
    {
        $this->hasOne(Student::class, 'user_id');
    }

    public function teacher()
    {
        $this->hasOne(Teacher::class, 'user_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

/* END OF PUBLIC FUNCTIONS ****************************************************/

    private function build_Phone_Array() : array
    {
        $phones = [];

        if(strlen(self::getPhoneHomeAttribute())){
            $phones[] = self::getPhoneHomeAttribute().' (h)';
        }

        if(strlen(self::getPhoneMobileAttribute())){
            $phones[] = self::getPhoneMobileAttribute().' (c)';
        }

        if(strlen(self::getPhoneWorkAttribute())){
            $phones[] = self::getPhoneWorkAttribute().' (w)';
        }

        return $phones;
    }
    /**
     * NOTE: THIS DIFFERS FROM THE SAME IMPLEMENTATION ON StudentFolder.info
     *
     * @param string $type = alternate/primary
     * @return Email
     */
    private function email_Object($type) //: Email
    {
        $subscribers = ['other','personal','work',];

        return (in_array($type, $subscribers))
            ? Subscriberemail::where('user_id', $this->user_id)
                ->where('emailtype_id', '=', Emailtype::where('descr', $type)->first()->id)
                ->first()
            : Nonsubscriberemail::where('user_id', $this->user_id)
                ->where('emailtype_id', '=', Emailtype::where('descr', $type)->first()->id)
                ->first();
        /*return  (count(DB::table((in_array($type, $subscribers) ? 'subscriberemails' : 'nonsubscriberemails')
                ->select('id')
                ->where('user_id', '=', $this->user_id)
                ->where('emailtype_id', '=', Emailtype::where('descr', $type)->first()->id)
                ->get()))
                //teacher email
                ? Email::firstWhere([
                    'teacher_user_id' => $this->user_id,
                    'type' => $type,
                    ])
                //student email
                : Email::find(DB::table('email_person') //student
                    ->select('email_id')
                    ->where('user_id', '=', $this->user_id)
                    ->where('type', '=', $type)
                    ->value('email_id'));
        */
    }

    private function formattedPhone($str) : string
    {
        return self::formatPhone($str ?? '');
    }

}
