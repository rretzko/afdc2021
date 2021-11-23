<?php

namespace App\Exports;

use App\Models\Emailtype;
use App\Models\Eventensembleparticipant;
use App\Models\Nonsubscriberemail;
use App\Models\Phone;
use App\Models\Phonetype;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantsExport implements FromCollection, WithHeadings, WithMapping
{
    private $participants;

    public function __construct(\App\Models\Eventversion $eventversion, \App\Models\Eventensemble $eventensemble) {

        $this->participants = $eventensemble->participatingRegistrants($eventversion);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->participants;
    }

    public function headings(): array
    {
        return [
            'id',
            'last',
            'first',
            'middle',
            'voice part',
            'score',
            'grade',
            'student-email-school',
            'student-email-personal',
            'student-phone-cell',
            'student-phone-home',
            'school',
            'teacher',
            'teacher-email-work',
            'teacher-email-personal',
            'teacher-email-other',
            'teacher-phone-cell',
            'teacher-phone-work',
            'teacher-phone-home',
            'guardian-1',
            'guaridan-1-email-primary',
            'guardian-1-email-alternate',
            'guardian-1-phone-cell',
            'guardian-1-phone-work',
            'guardian-1-phone-home'
        ];
        $test = [
            'Guardian.2.first',
            'Guardian.2.middle',
            'Guardian.2.last',
            'Guardian.2.EmailPrimary',
            'Guardian.2.EmailAlternate',
            'Guardian.2.PhoneCell',
            'Guardian.2.PhoneWork',
            'Guardian.2.PhoneHome',
            'Guardian.3.first',
            'Guardian.3.middle',
            'Guardian.3.last',
            'Guardian.3.EmailPrimary',
            'Guardian.3.EmailAlternate',
            'Guardian.3.PhoneCell',
            'Guardian.3.PhoneWork',
            'Guardian.3.PhoneHome',

        ];
    }

    public function map($participant): array
    {
        return [
            $participant->id,
            $participant->student->person->last,
            $participant->student->person->first,
            $participant->student->person->middle,
            $participant->instrumentations()->first()->formattedDescr(),
            $participant->grandtotal(),
            $participant->student->grade,
            $participant->student->emailSchool->id ? $participant->student->emailSchool->email : '',
            $participant->student->emailPersonal->id ? $participant->student->emailPersonal->email : '',
            $participant->student->phoneMobile->id ? $participant->student->phoneMobile->phone : '',
            $participant->student->phoneHome->id ? $participant->student->phoneHome->phone : '',
            $participant->student->currentSchool->name,
            $participant->student->currentTeacher->person->fullName(),
            $participant->student->currentTeacher->person->subscriberemailwork,
            $participant->student->currentTeacher->person->subscriberemailpersonal,
            $participant->student->currentTeacher->person->subscriberemailother,
            $participant->student->currentTeacher->person->phoneMobile,
            $participant->student->currentTeacher->person->phoneWork,
            $participant->student->currentTeacher->person->phoneHome,
            $this->guardianName($participant, 0),
            $this->guardianEmailPrimary($participant, 0),
            $this->guardianEmailAlternate($participant, 0),
            $this->guardianPhoneMobile($participant, 0),
            $this->guardianPhoneWork($participant, 0),
            $this->guardianPhoneHome($participant, 0),
        ];
        $s = Student::find($student['user_id']);

        $a = [
            $s->person->user->username,
            $s->person->first,
            $s->person->middle,
            $s->person->last,
            $s->classof,
            $s->birthday,
            $s->emailSchool->email,
            $s->emailPersonal->id ? $s->emailPersonal->email : '',
            $s->phoneMobile->id ? $s->phoneMobile->phone : '',
            $s->phoneHome->id ? $s->phoneHome->phone : '',
            $s->person->user->instrumentations->first()->formattedDescr(),
        ];

        foreach($s->guardians AS $guardian){
            $a[] = $guardian->person->first;
            $a[] = $guardian->person->middle;
            $a[] = $guardian->person->last;
            $a[] = $guardian->emailPrimary->id ? $guardian->emailPrimary->email : '';
            $a[] = $guardian->emailAlternate->id ? $guardian->emailAlternate->email : '';
            $a[] = $guardian->phoneMobile->id ? $guardian->phoneMobile->phone : '';
            $a[] = $guardian->phoneWork->id ? $guardian->phoneWork->phone : '';
            $a[] = $guardian->phoneHome->id ? $guardian->phoneHome->phone : '';
        }

        return $a;
    }

    private function guardianEmailAlternate($participant, $index)
    {
        if($participant->student->guardians->count() &&
            $participant->student->guardians[$index]){

            $email = Nonsubscriberemail::where('user_id', $participant->student->guardians[$index]->user_id)
                ->where('emailtype_id',Emailtype::GUARDIAN_ALTERNATE)
                ->first() ?? new Nonsubscriberemail;

            return $email->id ? $email->email : '';
        }

        return '';
    }

    private function guardianEmailPrimary($participant, $index)
    {
        if($participant->student->guardians->count() &&
            $participant->student->guardians[$index]){


            $email = Nonsubscriberemail::where('user_id', $participant->student->guardians[$index]->user_id)
                ->where('emailtype_id',Emailtype::GUARDIAN_PRIMARY)
                ->first() ?? new Nonsubscriberemail;

            return $email->id ? $email->email : '';
        }

        return '';
    }

    private function guardianName($participant, $index)
    {
        if($participant->student->guardians->count() && $participant->student->guardians[$index]){

            return $participant->student->guardians[$index]->person->fullName();
        }

        return 'None found';
    }

    private function guardianPhoneHome($participant, $index)
    {
        if($participant->student->guardians->count() &&
            $participant->student->guardians[$index]){

            $phone = Phone::where('user_id', $participant->student->guardians[$index]->user_id)
                    ->where('phonetype_id', Phonetype::PHONE_GUARDIAN_HOME)
                    ->first() ?? new Phone;

            return $phone->id ? $phone->phone : '';
        }

        return '';
    }

    private function guardianPhoneMobile($participant, $index)
    {
        if($participant->student->guardians->count() &&
            $participant->student->guardians[$index]){

            $phone = Phone::where('user_id', $participant->student->guardians[$index]->user_id)
                    ->where('phonetype_id', Phonetype::PHONE_GUARDIAN_MOBILE)
                    ->first() ?? new Phone;

            return $phone->id ? $phone->phone : '';
        }

        return '';
    }

    private function guardianPhoneWork($participant, $index)
    {
        if($participant->student->guardians->count() &&
            $participant->student->guardians[$index]){

            $phone = Phone::where('user_id', $participant->student->guardians[$index]->user_id)
                    ->where('phonetype_id', Phonetype::PHONE_GUARDIAN_WORK)
                    ->first() ?? new Phone;

            return $phone->id ? $phone->phone : '';
        }

        return '';
    }
}
