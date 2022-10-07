<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembershipExport implements FromCollection, WithHeadings, WithMapping
{
    private $memberships;

    public function __construct(\App\Models\Eventversion $eventversion) {

        $this->memberships = $eventversion->event->organization->memberships->sortBy('user.person.last');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->memberships;
    }

    public function headings(): array
    {
        return [
            'id',
            'last',
            'first',
            'middle',
            'username',
            'email1',
            'email2',
            'school1',
            'school2',
            'school3',
            'expiration',
        ];

    }

    public function map($membership): array
    {
        return [
            $membership->id,
            $membership->user['person']->last,
            $membership->user['person']->first,
            $membership->user['person']->middle,
            $membership->user->username,
            $membership->user['person']->subscriberemailwork,
            $membership->user['person']->subscriberemailpersonal,
            ($membership->user->schools->count()) ? $membership->user->schools[0]->name : '',
            ($membership->user->schools->count() > 1) ? $membership->user->schools[1]->name : '',
            ($membership->user->schools->count() > 2) ? $membership->user->schools[2]->name : '',
            $membership->expiration,
            ];

        return $a;
    }
}
