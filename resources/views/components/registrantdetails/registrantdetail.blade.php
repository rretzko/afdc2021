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
                    <td style="text-align: left;">{{ $registrant['schoolName'] }}</td>
                    <td style="text-align: left;">{{ $registrant['fullName'] }}</td>
                    <td>{{ $registrant['registrantId'] }}</td>
                    <td style="text-align: left;">
                        <a href="mailto:{{ $registrant['teacherEmail'] }}
                            ?subject={{ $registrant['fullName'] }} missing information
                            &body=Hi {{ $registrant['teacherFirstName'] }} - %0D%0A%0D%0A***** THE FOLLOWING INFORMATION MUST BE UPDATED BY MONDAY, DECEMBER 6th *****%0D%0A%0D%0APlease update the following missing information for {{ $registrant['fullName'] }}%0D%0AEmails: {{ $registrant['emails'] ?: '*** missing ***' }}%0D%0APhones: {{ $registrant['phones'] ?: '*** missing ***' }}%0D%0AParent/Guardian: $registrant['guardian'] }}%0D%0AParent/Guardian Emails: {{ $registrant['guardianEmails'] }}%0D%0AParent/Guardian Phone: {{ $registrant['guardianPhones'] }}%0D%0ATo update this information, please go to TheDirectorsRoom.com, click the Students (menu item), and click the Edit button by the student\'s name.%0D%0AOR...%0D%0A%0D%0APlease have the student update their profile on StudentFolder.info.%0D%0AThank you for your immediate attention to this!%0D%0A%0D%0A{{ auth()->user()->person->fullName() }}, Registration Manager%0D%0A{{ $eventversion->name }}">
                            {{ $registrant['teacherName'] }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('registrationmanagers.registrantdetails.changeVoicePart', ['registrant' => $registrant['registrantId']]) }}" style="color: #007bff">
                            {{ $registrant['voicePart'] }}
                        </a>
                    </td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if( strpos($registrant['emails'], 'MISSING')) color: red; @endif">Emails</td>
                    <td colspan="4" style="text-align: left; @if( strpos($registrant['emails'], 'MISSING')) color: red; @endif">
                        {{ $registrant['emails'] }}
                    </td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if(strpos($registrant['phones'], 'MISSING')) color: red; @endif">Phones</td>
                    <td colspan="4" style="text-align: left; @if(strpos($registrant['phones'], 'MISSING')) color: red; @endif">{{ $registrant['phones'] }}</td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if(! $registrant['guardian']) color: red; @endif">Parent/Guardian</td>
                    <td colspan="4" style="text-align: left;">{{ $registrant['guardian'].' ('.$registrant['guardianId'].') '}} </td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if( strpos($registrant['guardianEmails'], 'MISSING')) color: red; @endif ">
                        Parent/Guardian Email
                    </td>
                    <td colspan="4" style="text-align: left; @if( strpos($registrant['guardianEmails'], 'MISSING')) color: red; @endif">
                        {{ $registrant['guardianEmails'] }}
                    </td>
                </tr>
                <tr style="font-size: .8rem;">
                    <td></td>
                    <td style="text-align: right; @if((! $registrant['guardianPhones'])) color: red; @endif">
                        Parent/Guardian Phone
                    </td>
                    <td colspan="4" style="text-align: left; @if( strpos($registrant['guardianPhones'], 'MISSING')) color: red; @endif">
                        {{ $registrant['guardianPhones'] }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

</div>
