<style>
    table{width: 100%; border-collapse: collapse; padding: .25rem;}
    td,th{border-left: 1px solid black; border-right: 1px solid black;}
    .page_break{page-break-before: always;}
</style>
@foreach($registrants AS $registrant)
    <div style="display: flex; flex-direction: row;">
        <table>
            <tr>
                <td  colspan="2" style="width: 200px; min-width: 200px; max-width: 200px; font-weight: bold; border-top: 1px solid black;">
                    {{ $eventversion->name }}
                </td>
                <td style="color: white;">-</td>
                <td colspan="2" style="width: 200px; min-width: 200px; max-width: 200px; font-weight: bold; border-top: 1px solid black;">
                    {{ $eventversion->name }}
                </td>
            </tr>
            <tr style="font-weight: bold; text-transform: uppercase;">
                <td colspan="2">{{ $rooms[0]->descr }}</td>
                <td style="color: white;">-</td>
                <td colspan="2">{{ $rooms[1]->descr }}</td>
            </tr>
            <tr style="margin: 0.5rem 0; border-right: 1px solid black;">
                <td style="border-right: 0; background-color: lightgray;">
                    Aud # <b>{{ $registrant->id }}</b>
                </td>
                <td style="border-left: 0; font-weight: bold; background-color: lightgray;">
                    {{ $instrumentation->formattedDescr() }}
                </td>
                <td style="color: white;">-</td>
                <td  style="border-right: 0; background-color: lightgray;">
                    Aud # <b>{{ $registrant->id }}</b>
                </td>
                <td style="border-left: 0; border-right: 1px solid black; font-weight: bold; background-color: lightgray;">
                    {{ $instrumentation->formattedDescr() }}
                </td>
            <tr>
                <td colspan="2" style="text-align: center; font-size: 1.5rem; font-weight: bold; ">
                    {{ $registrant->student->person->fullnameAlpha() }}
                </td>
                <td style="color: white;">-</td>
                <td colspan="2" style="text-align: center; font-size: 1.5rem; font-weight: bold;">
                    {{ $registrant->student->person->fullnameAlpha() }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    {{ $registrant->student->emails->count() ? $registrant->student->emails->first()->email : 'No email found' }}
                </td>
                <td style="color: white;">-</td>
                <td colspan="2" style="text-align: center; ">
                    {{ $registrant->student->emails->count() ? $registrant->student->emails->first()->email : 'No email found' }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    {{ $registrant->timeslot }}
                </td>
                <td style="color: white;">-</td>
                <td colspan="2" style="text-align: center;">
                    {{ $registrant->timeslot }}
                </td>
            </tr>
            <tr>
                <td colspan=2" style="text-align: center; border-bottom: 1px solid black;">
                    {{ $registrant->student->currentSchool->shortName }}
                </td>
                <td style="color: white;">-</td>
                <td colspan="2" style="text-align: center; border-left: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;">
                    {{ $registrant->student->currentSchool->shortName }}
                </td>
            </tr>

            </tr>
        </table>
    </div>

    @if(! ($loop->iteration % 5) )
        <div class="page_break"></div>
    @endif

@endforeach
