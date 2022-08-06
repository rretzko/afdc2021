<?php

namespace App\Services;


use App\Models\Eventversion;
use App\Models\Organization;
use App\Models\Person;
use App\Models\Userconfig;
use FontLib\TrueType\Collection;

class SubscriberMatchService
{
    private $emails;
    private $memberships;
    private $table;

    public function __construct(\Illuminate\Database\Eloquent\Collection $emails)
    {
        $this->emails = $emails;
        $this->memberships = collect();
        $this->table = '';

        $this->buildMemberships();

        $this->init();
    }

    public function table(): string
    {
        return $this->table;
    }

/** END OF PUBLIC FUNCTIONS  ================================================*/

    private function action(Person $person): string
    {
        $str = '';

        if($this->memberships->contains($person->user_id)){

            $str .= '<a href="">';

            $str .= '<button style="background-color: rgba(255,0,0,0.1); border: 1px solid darkred; color: darkred; border-radius: 0.5rem;">';

            $str .= 'Remove';

            $str .= '</button>';

            $str .= '</a>';

        }else {

            $str .= '<a href="" >';

            $str .= '<button style="background-color: rgba(0,255,0,0.1); border: 1px solid darkgreen; color: darkgreen; border-radius: 0.5rem;">';

            $str .= 'Add';

            $str .= '</button>';

            $str  .= '</a>';
        }

        return $str;
    }

    private function buildMemberships()
    {
        $eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $organization = Organization::find($eventversion->event->organization)->first();
        $this->memberships = $organization->memberships;
    }

    private function emailRows(): string
    {
        $str = '';
        $cntr = 1;

        foreach($this->emails AS $email){

            $person = Person::find($email->user_id);
            $fullnamealpha = $person->fullnameAlpha();
            $emailemail = $email->email;

            $str .= '<tr>';
            $str .= '<td>'.$cntr.'</td>';
            $str .= '<td title="'.$fullnamealpha.'">'.substr($fullnamealpha,0, 40).' ('.$person->user_id.')</td>';
            $str .= '<td title="'.$emailemail.'">'.substr($emailemail,0,40).'</td>';
            $str .= '<td>'.$this->schoolsCell($person).'</td>';
            $str .= '<td>'.$this->action($person).'</td>';
            $str .= '</tr>';

            $cntr++;
        }

        return $str;
    }

    private function headers(): string
    {
        $str = '<tr>';

        $str .= '<th style="width: 5%;">id</th>';

        $str .= '<th style="width: 20%;">name</th>';

        $str .= '<th style="width: 30%;">email</th>';

        $str .= '<th style="width: 30%;">school</th>';

        $str .= '<th style="width: 15%;">action</th>';

        $str .= '</tr>';

        return $str;
    }

    private function init(): void
    {
        $rows = $this->rows();

        if($rows) {

            $this->table = '<table style="border-spacing: 0;">';

            $this->table .= $this->headers();

            $this->table .= $rows;

            $this->table .= '</table>';
        }
    }

    private function rows(): string
    {
        $str = $this->emailRows();

        return $str;
    }

    private function schoolsCell(Person $person): string
    {
        $str = '';

        foreach($person->user->schools AS $school){

            $schoolname = $school->name;

            $str .= '<div title="'.$schoolname.'">'.substr($school->name,0,40).'</div>';
        }

        return $str;
    }
}
