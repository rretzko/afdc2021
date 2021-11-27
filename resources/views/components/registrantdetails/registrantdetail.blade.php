@props([
    'eventversion',
    'instrumentation',
    'registrants',
])
<div style="border: 1px solid black; padding: .25rem; margin: .25rem;">

    <div style="text-align: center; font-weight: bold;">
        {{ $eventversion->name }}
    </div>

    <div style="text-align: center; font-weight: bold; background-color: rgba(0,0,0,.1); margin-bottom: .5rem; border-bottom: 1px solid black; border-top: 1px solid black;">
        {{ $instrumentation->formattedDescr() }}
    </div>

    <div style="">
        <style>
            table{border-collapse: collapse; width: available;margin: auto; }
            td,th{border: 1px solid black; padding: 0 .25rem; text-align: center;}
        </style>

        <table>
            <thead>
            <tr>
                <th>###</th>
                <th>School</th>
                <th>Name</th>
                <th>Reg.Id</th>
                <th>Teacher</th>
                <th>Voice Part</th>
            </tr>
            </thead>

            <tbody>
            @foreach($registrants AS $registrant)
                <tr style="font-weight: bold;">
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: left;">{{ $registrant->student->currentSchool->name }}</td>
                    <td style="text-align: left;">{{ $registrant->student->person->fullnameAlpha() }}</td>
                    <td>{{ $registrant->id }}</td>
                    <td style="text-align: left;">
                        @if($registrant->student->currentTeacher)
                            @if($registrant->student->currentTeacher->person->subscriberemails)
                                <a href="mailto:{{ $registrant->student->currentTeacher->person->subscriberemails->first()->email }}
                                    ?subject={{ $registrant->student->person->fullName() }} missing information
                                    &body=Hi {{ $registrant->student->currentTeacher->person->first }} - %0D%0APlease update the following missing information for {{ $registrant->student->person->fullName() }}%0D%0AEmails: {{ $registrant->student->emailsCsv ?: '*** missing ***' }}%0D%0APhones: {{ $registrant->student->phonesCsv ?: '*** missing ***' }}%0D%0AParent/Guardian: {{ $registrant->student->guardians->count() ? $registrant->student->guardians->first()->person->fullName() : '*** missing ***' }}%0D%0AParent/Guardian Emails: {{ ($registrant->student->guardians->count() && $registrant->student->guardians->first()->emailsCsv) ?  $registrant->student->guardians->first()->emailsCsv : '*** missing ***'}}%0D%0AParent/Guardian Phone: {{ ($registrant->student->guardians->count() && $registrant->student->guardians->first()->phonesCsv) ?  $registrant->student->guardians->first()->phonesCsv : '*** missing ***'}}%0D%0A">
                                    {{ $registrant->student->currentTeacher->person->fullName()}}
                                </a>
                            @else
                                <span title="No email found for {{ $registrant->student->currentTeacher->person->fullName() }}">
                                    {{ $registrant->student->currentTeacher->person->fullName() }}
                                </span>
                            @endif
                        @else
                         '*** ERROR ***
                        @endif
                    </td>
                    <td>{{ $registrant->instrumentations->first()->formattedDescr() }}</td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if(! strlen($registrant->student->emailsCsv)) color: red; @endif">Emails</td>
                    <td colspan="4" style="text-align: left;">
                        {{ $registrant->student->emailsCsv ?: '*** missing ***'}}
                    </td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if(! strlen($registrant->student->phonesCsv)) color: red; @endif">Phones</td>
                    <td colspan="4" style="text-align: left;">{{ $registrant->student->phonesCsv ?: '*** missing ***' }}</td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if(! $registrant->student->guardians->count()) color: red; @endif">Parent/Guardian</td>
                    <td colspan="4" style="text-align: left;">{{ $registrant->student->guardians->count() ? $registrant->student->guardians->first()->person->fullname() : '*** missing ***'}}</td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if((! $registrant->student->guardians->count()) || (! $registrant->student->guardians->first()->emailsCsv)) color: red; @endif">Parent/Guardian Email</td>
                    <td colspan="4" style="text-align: left;">{{ ($registrant->student->guardians->count() && $registrant->student->guardians->first()->emailCsv) ? $registrant->student->guardians->first()->emailsCsv : '*** missing ***'}}</td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if((! $registrant->student->guardians->count()) || (! $registrant->student->guardians->first()->phonesCsv)) color: red; @endif">Parent/Guardian Phone</td>
                    <td colspan="4" style="text-align: left;">{{ ($registrant->student->guardians->count() && $registrant->student->guardians->first()->phoneCsv) ? $registrant->student->guardians->first()->phonesCsv : '*** missing ***'}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

</div>
