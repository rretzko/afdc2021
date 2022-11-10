<style>
    table{width: 100%; border-collapse: collapse; padding: .25rem;}
    td,th{text-align: center; height: 1.33rem;}
    .page_break{page-break-before: always;}

</style>
@foreach($registrants AS $registrant)
{{-- @for($i=1; $i<11; $i++) --}}
    <div>

        <table>
            <tr>
                <td>
                    {{-- LEFT SIDE --}}
                    <table style="border: 1px solid black; ">
                <tr>
                    <td colspan="2" style="border: 1px solid black; font-weight: bold;">{{ $registrant->student->currentSchool->shortName }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 1px solid black; background-color: rgba(0,0,0,0.1); font-weight: bold;">{{ $eventversion->name }}</td>
                </tr>
                <tr>
                    <td colspan="2">{{ $registrant->student->person->fullNameAlpha() }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div style="margin: 0 2rem; border: 1px solid black;">Registration Number: {{ $registrant->id }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; text-align: left; padding-left: 1rem;">Scales/Arpeggio/Quartet</td>
                    <td style="font-weight: bold; text-align: right; padding-right: 1rem;">{{ $registrant->instrumentations->first()->formattedDescr() }}</td>
                </tr>
                <tr>
                    <td colspan="2">Total Score ____________________</td>
                </tr>
                <tr>
                    <td>Accepted (  )</td>
                    <td>Not Accepted (  )</td>
                </tr>
            </table>
                </td>
                <td>
                    {{-- RIGHT SIDE --}}
                    <table style="border: 1px solid black; ">
            <tr>
                <td colspan="2" style="border: 1px solid black; font-weight: bold;">{{ $registrant->student->currentSchool->shortName }}</td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black; background-color: rgba(0,0,0,0.1); font-weight: bold;">{{ $eventversion->name }}</td>
            </tr>
            <tr>
                <td colspan="2">{{ $registrant->student->person->fullNameAlpha() }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="margin: 0 2rem; border: 1px solid black;">Registration Number: {{ $registrant->id }}</div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; text-align: left; padding-left: 1rem;">Solo</td>
                <td style="font-weight: bold; text-align: right; padding-right: 1rem;">{{ $registrant->instrumentations->first()->formattedDescr() }}</td>
            </tr>
            <tr>
                <td colspan="2">Total Score ____________________</td>
            </tr>
            <tr>
                <td>Accepted (  )</td>
                <td>Not Accepted (  )</td>
            </tr>
        </table>
                </td>
            </tr>
        </table>

    </div>

    @if(! ($loop->iteration % 5) )
        <div class="page_break"></div>
    @endif

 @endforeach
