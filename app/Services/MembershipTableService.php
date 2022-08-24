<?php

namespace App\Services;

use App\Models\Datetype;
use App\Models\Eventversion;
use App\Models\Membership;
use App\Models\Person;
use App\Models\User;
use App\Models\Userconfig;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipTableService extends Model
{
    use HasFactory;

    private $datatable;
    private $eventversion;
    private $organization;

    public function __construct()
    {
        $this->datatable = '';
        $this->eventversion = Eventversion::find(Userconfig::getValue('eventversion', auth()->id()));
        $this->organization = $this->eventversion->event->organization;

        $this->init();
    }

    public function datatable(): string
    {
        return $this->datatable;
    }

/** END OF PUBLIC FUNCTIONS **************************************************/

    private function btnEdit(Membership $membership): string
    {
        return '<a href="/member/edit/'.$membership->id.'" class="btn-edit" onMouseOver="this.style.color=\'darkred\'"; onMouseOut="this.style.color=\'indigo\'"; >
                    Edit
                </a>';
    }

    private function btnRemove(Membership $membership): string
    {
        return '<a class="btn-remove" href="/member/remove/'.$membership->id.'"  onMouseOver="this.style.color=\'black\'"; onMouseOut="this.style.color=\'darkred\'"; >
                    Remove
                </a>';
    }

    private function buildDataTable(Collection $memberships): string
    {
        $str = $this->style();

        $str .= '<h4 style="text-align: center;">'.$this->organization->name.' Membership ('.$memberships->count().')</h4>';

        $str .= '<table cell-spacing="0" >';

        $str .= $this->headers();

        $str .= $this->rows($memberships);

        $str .= '</table>';

        return $str;
    }

    private function calcPastDue(string $expiration): string
    {
        return ($expiration < $this->eventversion->eventversiondates->where('datetype_id', Datetype::MEMBERSHIP_VALID)->first()->dt)
            ? 'pastdue'
            : '';
    }

    private function emails(Person $person) : string
    {
        $str = '';

        foreach($person->subscriberemails AS $email){
            $str .= '<div>'.$email->email.'</div>';
        }

        return $str;
    }

    private function headers(): string
    {
        $str = '<tr>';

        $str .= '<th>###</th>';

        $str .= '<th>Name</th>';

        $str .= '<th>Username</th>';

        $str .= '<th>Emails</th>';

        $str .= '<th>Schools</th>';

        $str .= '<th>Expiration</th>';

        $str .= '<th class="sr-only" >Edit</th>';

        $str .= '<th class="sr-only">Remove</th>';

        $str .= '</tr>';

        return $str;
    }

    private function init(): void
    {
        $memberships = Membership::with('user')->where('organization_id', $this->organization->id)->get();

        $this->datatable = $this->buildDataTable($memberships);
    }

    private function rows(Collection $memberships) : string
    {
        $cntr = 1;
        $str = '';

        foreach($memberships->sortBy(['user.person.last','user.person.first']) AS $membership){

            $user = $membership['user'];

            $str .= '<tr>';

            $str .= '<td>'.$cntr.'</td>';

            $str .= '<td>'.$user->person->fullnameAlpha().'</td>';

            $str .= '<td>'.$user->username.'</td>';

            $str .= '<td>'.$this->emails($user->person).'</td>';

            $str .= '<td>'.$this->schools($user).'</td>';

            $str .= '<td class="'.$this->calcPastDue($membership->expiration).'">'.$membership->expirationMDYFull.'</td>';

            $str .= '<td>'.$this->btnEdit($membership).'</td>';

            $str .= '<td>'.$this->btnRemove($membership).'</td>';

            $str .= '</tr>';

            $cntr++;
        }

        return $str;
    }

    private function schools(User $user) : string
    {
        $str = '';

        foreach($user->schools AS $school) {
            if (! str_contains($school->name, 'Studio')){
                $str .= '<div>' . $school->name . '</div>';
            }
        }

        return $str;
    }

    private function style(): string
    {
        $str = '<style>';

        $str .= 'table{border-collapse: collapse;}';

        $str .= 'caption{border: 1px solid black; font-weight: bold; text-align: center;}';

        $str .= 'td,th{border: 1px solid black; padding: 0 0.2rem;}';

        $str .= '.pastdue{color: red;}';

        $str .= 'a.btn-edit{background-color: lavender !important; color: indigo !important; border: 1px solid indigo; padding: 0.2rem; border-radius: 0.5rem;}';

        $str .= 'a.btn-remove{background-color: lavenderblush !important; color: darkred !important; border: 1px solid darkred; padding: 0.2rem; border-radius: 0.5rem;}';

        $str .= '</style>';

        return $str;
    }
}
