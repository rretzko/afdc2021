@props([
'eventversion',
'instrumentation',
'registrant',
'room',
])
<div style="border-bottom: 1px solid black;">
    <div style="border-top: 1px solid black;">
        {{ $eventversion->name }}
    </div>
    <div style="font-weight: bold;">
        {{ strtoupper($room->descr) }}
    </div>
    <div style="margin: 0.5rem; 0">
        <table style="border: 0; width: 100%; padding: 0;">
            <tr style=" background-color: lightgrey;">
                <td style="border: 0; width: 50%; padding-left: 0.5rem;">Aud # <b>{{ $registrant->id  }}</b></td>
                <td style="border: 0; text-align: center;">{{ $instrumentation->formattedDescr() }}</td>
            </tr>
        </table>
    </div>
    <div style="text-align: center; font-size: 1.5rem; font-weight: bold; border-right: 0;">
        {{ (strlen($registrant->student->person->fullnameAlpha()) < 22)
            ? $registrant->student->person->fullnameAlpha()
            : substr($registrant->student->person->fullnameAlpha(), 0, 21) }}
    </div>
    <div style="text-align: center;">
        {{ $registrant->student->emails->count() ? $registrant->student->emails->first()->email : 'No email found' }}
    </div>
    <div style="text-align: center;">
        {{ $registrant->timeslot }}
    </div>
    <div style="text-align: center;">
        {{ $registrant->student->currentSchool->shortName }}
    </div>
</div>

