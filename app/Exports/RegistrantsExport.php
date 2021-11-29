<?php

namespace App\Exports;

use App\Models\Emailtype;
use App\Models\Nonsubscriberemail;
use App\Models\Phone;
use App\Models\Phonetype;
use App\Models\Utility\RegistrationActivity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrantsExport implements FromCollection, WithHeadings, WithMapping
{
    private $registrants;

    public function __construct(\App\Models\Eventversion $eventversion, \App\Models\Instrumentation $instrumentation) {

        $ra = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);
        $this->registrants = $ra->registrantsByTimeslotSchoolNameFullnameAlpha($instrumentation);
    }

    public function collection()
    {
        return $this->registrants;
    }

    public function headings(): array
    {
        return [
            'id',
            'last',
            'first',
            'middle',
            'voice part',
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
    }

    public function map($registrant): array
    {if($registrant->user_id === 1470){dd($registrant->student->currentTeacher);}
        return [
            $registrant->id,
            $registrant->student->person->last,
            $registrant->student->person->first,
            $registrant->student->person->middle,
            $registrant->instrumentations()->first()->formattedDescr(),
            $registrant->student->grade,
            $registrant->student->emailSchool->id ? $registrant->student->emailSchool->email : '',
            $registrant->student->emailPersonal->id ? $registrant->student->emailPersonal->email : '',
            $registrant->student->phoneMobile->id ? $registrant->student->phoneMobile->phone : '',
            $registrant->student->phoneHome->id ? $registrant->student->phoneHome->phone : '',
            $registrant->student->currentSchool->name,
            $registrant->student->currentTeacher ? $registrant->student->currentTeacher->person->fullName() : '*** missing ***',
            $registrant->student->currentTeacher ? $registrant->student->currentTeacher->person->subscriberemailwork : '*** missing ***',
            $registrant->student->currentTeacher ? $registrant->student->currentTeacher->person->subscriberemailpersonal : '*** missing ***',
            $registrant->student->currentTeacher ? $registrant->student->currentTeacher->person->subscriberemailother : '*** missing ***',
            $registrant->student->currentTeacher ? $registrant->student->currentTeacher->person->phoneMobile : '*** missing ***',
            $registrant->student->currentTeacher ? $registrant->student->currentTeacher->person->phoneWork : '*** missing ***',
            $registrant->student->currentTeacher ? $registrant->student->currentTeacher->person->phoneHome : '*** missing ***',
            $this->guardianName($registrant, 0),
            $this->guardianEmailPrimary($registrant, 0),
            $this->guardianEmailAlternate($registrant, 0),
            $this->guardianPhoneMobile($registrant, 0),
            $this->guardianPhoneWork($registrant, 0),
            $this->guardianPhoneHome($registrant, 0),
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

        private function guardianEmailAlternate($registrant, $index)
    {
        if($registrant->student->guardians->count() &&
            $registrant->student->guardians[$index]){

            $email = Nonsubscriberemail::where('user_id', $registrant->student->guardians[$index]->user_id)
                    ->where('emailtype_id',Emailtype::GUARDIAN_ALTERNATE)
                    ->first() ?? new Nonsubscriberemail;

            return $email->id ? $email->email : '';
        }

        return '';
    }

        private function guardianEmailPrimary($registrant, $index)
    {
        if($registrant->student->guardians->count() &&
            $registrant->student->guardians[$index]){


            $email = Nonsubscriberemail::where('user_id', $registrant->student->guardians[$index]->user_id)
                    ->where('emailtype_id',Emailtype::GUARDIAN_PRIMARY)
                    ->first() ?? new Nonsubscriberemail;

            return $email->id ? $email->email : '';
        }

        return '';
    }

        private function guardianName($registrant, $index)
    {
        if($registrant->student->guardians->count() && $registrant->student->guardians[$index]){

            return $registrant->student->guardians[$index]->person->fullName();
        }

        return 'None found';
    }

        private function guardianPhoneHome($registrant, $index)
    {
        if($registrant->student->guardians->count() &&
            $registrant->student->guardians[$index]){

            $phone = Phone::where('user_id', $registrant->student->guardians[$index]->user_id)
                    ->where('phonetype_id', Phonetype::PHONE_GUARDIAN_HOME)
                    ->first() ?? new Phone;

            return $phone->id ? $phone->phone : '';
        }

        return '';
    }

        private function guardianPhoneMobile($registrant, $index)
    {
        if($registrant->student->guardians->count() &&
            $registrant->student->guardians[$index]){

            $phone = Phone::where('user_id', $registrant->student->guardians[$index]->user_id)
                    ->where('phonetype_id', Phonetype::PHONE_GUARDIAN_MOBILE)
                    ->first() ?? new Phone;

            return $phone->id ? $phone->phone : '';
        }

        return '';
    }

        private function guardianPhoneWork($registrant, $index)
    {
        if($registrant->student->guardians->count() &&
            $registrant->student->guardians[$index]){

            $phone = Phone::where('user_id', $registrant->student->guardians[$index]->user_id)
                    ->where('phonetype_id', Phonetype::PHONE_GUARDIAN_WORK)
                    ->first() ?? new Phone;

            return $phone->id ? $phone->phone : '';
        }

        return '';
    }


}
