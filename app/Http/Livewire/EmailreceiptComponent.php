<?php

namespace App\Http\Livewire;

use App\Models\Emailclient;
use App\Models\Eventversion;
use App\Models\Person;
use App\Models\Registrant;
use App\Models\Registranttype;
use App\Models\School;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
use Livewire\Component;

class EmailreceiptComponent extends Component
{
    public $emailbody;
    public $emailbodyhtml = '';
    public $emailbodytext = '';
    public $eventversion;
    public $eventversionname = '';
    public $payerschool;
    public $payerteachers;
    public $sender = NULL;
    public $showemailreceiptmodal=false;
    public $toggle;
    public $payeruserid;

    public function mount($toggle)
    {
        $this->init();
        $this->payerschool = new School;
        $this->payerteachers = collect();
        $this->payeruserid = 0;
        $this->toggle = $toggle;
    }

    public function render()
    {
        return view('livewire.emailreceipt-component',
        [
            'eventversion' => $this->eventversion,
            'registrationactivity' => $this->registrationActivity(),
            'schools' => $this->targetSchools(),
        ]);
    }

    public function init()
    {
        $this->eventversionname = $this->eventversion->name;
        $this->sender = Person::find(auth()->id());
        $this->emailbody = $this->emailBody();
    }

    public function sendEmail()
    {
        $this->emailbodyhtml = $this->emailBodyHtml();
        $this->emailbodytext = $this->emailBodyText();

        $client = new Emailclient;

        $client->packageReceived(Person::find($this->payeruserid), $this->payerschool, $this->eventversion,
            $this->emailbodyhtml, $this->emailbodytext);
    }

    public function setSchool(School $school)
    {
        $this->payerschool = $school;
        $this->paymentteachers = $school->teachers;
        $this->payeruserid = $school->teachers->first()->user_id;
        $this->showemailreceiptmodal = true;
    }

    private function counties()
    {
        return [1,2,3,4,5,6,7,8,9,10, //includes "unknown" county
            11,12,13,14,15,16,17,18,19,20,21,22];
    }

    private function emailBody()
    {
        $signature = ($this->sender)
            ? $this->sender->fullname().', '.$this->eventversionname.' Registration Manager'
            : '';

        $str = "This notice is to advise you that we have received your ".$this->eventversionname." packet.\nThe packet has not yet been opened, but you will be notified if there are any questions or expected items missing.\n";
        $str .= $signature;

        return $str;
    }

    private function emailBodyHtml()
    {
        $signature = ($this->sender)
            ? $this->sender->fullname().'<br />'.$this->eventversionname.' Registration Manager'
            : '';

        $str = "<p>This notice is to advise you that we have received your ".$this->eventversionname." packet.</p>";
        $str .= "<p>The packet has not yet been opened, but you will be notified if there are any questions or expected items missing.</p>";
        $str .= $signature;

        return $str;
    }

    private function emailBodyText()
    {
        return $this->emailBody();
    }

    private function filterParticipatingSchools($schools)
    {
        return $schools->filter(function($school){
            return Registrant::where('school_id',$school->id)
                ->where('eventversion_id', $this->eventversion->id)
                ->where('registranttype_id', Registranttype::REGISTERED)
                ->count('id');
        });
    }

    private function myCounties(){

        $usercounties = [
            45 => [1,6,7,9,15,17,19,],
            56 => [4,11,12,16,20,],
            249 => [5,8,10,21,13,],
            423 => [2,3,14,18,]
        ];

        return array_key_exists(auth()->id(), $usercounties)
            ? $usercounties[auth()->id()]
            : [];
    }

    private function registrationActivity()
    {
        return new RegistrationActivity(
            [
                'eventversion' => $this->eventversion,
                'counties' => $this->targetCounties(),
            ]
        );
    }

    private function targetCounties()
    {
        return ($this->toggle === 'my')
            ? $this->myCounties()
            : $this->counties();
    }

    private function targetSchools()
    {
        $allschools = (($this->toggle === 'my') && count($this->myCounties()))
            ? $this->eventversion->schoolsByCounties($this->myCounties())
            : $this->eventversion->schools();

        return $this->filterParticipatingSchools($allschools);
    }


}
