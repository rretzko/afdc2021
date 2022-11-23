<style>
    table{width: 50%; border-collapse: collapse; padding: .25rem;}
    td,th{border-left: 1px solid black; border-right: 1px solid black;}
    .page_break{page-break-before: always;}
</style>
@foreach($registrants AS $registrant)
    <div style="">
        <table>
            <tr>
                <td style="font-weight: bold; border-top: 1px solid black;" colspan="3">{{ $eventversion->name }}</td>
                <td style="border: 1px solid black; border-bottom: 0; color: lightgrey; text-align: center;">TAB</td>
            </tr>
            <tr style="font-weight: bold; text-transform: uppercase;">
                <td colspan="3">{{ $rooms[0]->descr }}</td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; color: lightgrey; text-align: center; font-weight: normal">
                    ROOM
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-right: 0;">
                    Aud # <b>{{ $registrant->id }}</b>
                </td>
                <td style="border-left: 0; font-weight: bold;">
                    {{ $instrumentation->formattedDescr() }}
                </td>
                <td style="border: 1px solid black; border-top: 0; color: lightgrey; text-align: center;">ONLY</td>
            <tr>
                <td colspan="3" style="text-align: center; font-size: 1.5rem; font-weight: bold; border-right: 0;">
                    {{ $registrant->student->person->fullnameAlpha() }}
                </td>
                <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid white;"></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; border-right: 0;">
                    {{ $registrant->student->emails->count() ? $registrant->student->emails->first()->email : 'No email found' }}
                </td>
                <td style="border-right: 1px solid black; border-left: 1px solid white;"></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; border: 0; border-left: 1px solid black;">
                    {{ $registrant->timeslot }}
                </td>
                <td style="border-right: 1px solid black; border-left: 1px solid white;"></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; border: 0; border-left: 1px solid black; border-bottom: 1px solid black;">
                    {{ $registrant->student->currentSchool->shortName }}
                </td>
                <td style="border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid white;"></td>
            </tr>

            </tr>
        </table>
    </div>

    @if(! ($loop->iteration % 5) )
        <div class="page_break"></div>
    @endif

@endforeach
