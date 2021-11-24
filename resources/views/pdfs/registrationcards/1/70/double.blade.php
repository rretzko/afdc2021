<style>
    table{width: 100%; border-collapse: collapse; padding: .25rem;}
    td,th{border-left: 1px solid black; border-right: 1px solid black;}
    .page_break{page-break-before: always;}
</style>
@foreach($registrants AS $registrant)
    <div style="display: flex; flex-direction: row;">
        <table>
            <tr>
                <td style="font-weight: bold; border-top: 1px solid black;" colspan="3">{{ $eventversion->name }}</td>
                <td style="color: white;">-</td>
                <td style="font-weight: bold; border-top: 1px solid black;" colspan="3">{{ $eventversion->name }}</td>
                <td style="border: 1px solid black; border-bottom: 0; color: lightgrey; text-align: center;">TAB</td>
            </tr>
            <tr style="font-weight: bold; text-transform: uppercase;">
                <td colspan="3">{{ $rooms[0]->descr }}</td>
                <td style="color: white;">-</td>
                <td colspan="3">{{ $rooms[1]->descr }}</td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; color: lightgrey; text-align: center; font-weight: normal">
                    ROOM
                </td>
            </tr>
            <tr>
                <td style="border-right: 0;">
                    Aud #
                </td>
                <td style="border: 0; font-weight: bold;">
                    {{ $registrant->id }}
                </td>
                <td style="border-left: 0; font-weight: bold;">
                    {{ $instrumentation->formattedDescr() }}
                </td>
                <td style="color: white;">-</td>
                <td style="border-right: 0;">
                    Aud #
                </td>
                <td style="border: 0; font-weight: bold;">
                    {{ $registrant->id }}
                </td>
                <td style="border-left: 0; font-weight: bold;">
                    {{ $instrumentation->formattedDescr() }}
                </td>
                <td style="border: 1px solid black; border-top: 0; color: lightgrey; text-align: center;">ONLY</td>
                <tr>
                    <td colspan="3" style="text-align: center; font-size: 1.5rem; font-weight: bold;">
                        {{ $registrant->student->person->fullnameAlpha() }}
                    </td>
                    <td style="color: white;">-</td>
                    <td colspan="3" style="text-align: center; font-size: 1.5rem; font-weight: bold; border-right: 0;">
                        {{ $registrant->student->person->fullnameAlpha() }}
                    </td>
                    <td style="border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid white;"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        {{ $registrant->student->emails->count() ? $registrant->student->emails->first()->email : 'No email found' }}
                    </td>
                    <td style="color: white;">-</td>
                    <td colspan="3" style="text-align: center; border-right: 0;">
                        {{ $registrant->student->emails->count() ? $registrant->student->emails->first()->email : 'No email found' }}
                    </td>
                    <td style="border-right: 1px solid black; border-left: 1px solid white;"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        {{ $registrant->timeslot }}
                    </td>
                    <td style="color: white;">-</td>
                    <td colspan="3" style="text-align: center; border: 0;">
                        {{ $registrant->timeslot }}
                    </td>
                    <td style="border-right: 1px solid black; border-left: 1px solid white;"></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center; border-bottom: 1px solid black;">
                        {{ $registrant->student->currentSchool->shortName }}
                    </td>
                    <td style="color: white;">-</td>
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
<!-- {{--

    <div class="page_break"></div>
    <div style="display:flex; flex-direction:row;">
        {{-- LEFT BOX --
        <div style="border: 1px solid black; padding: .25rem; margin: .25rem;">
            {{-- Header --
            <div>
                {{ $eventversion->name }}
            </div>
            <div style="font-weight: bold; text-transform: uppercase;">
                {{ isset($rooms[0]) ? $rooms[0]->descr : 'No room found' }}
            </div>

            {{-- Aud# + Instrumentation --
            <div style="display: flex; ">
                <div style="display:flex; margin-right: 1rem;">
                    <label>Aud #</label>
                    <div class="data">{{ $registrant->id }}</div>
                </div>
                <div style="display: flex;">
                    <label></label>
                    <div class="data">{{ $instrumentation->formattedDescr() }}</div>
                </div>
            </div>

            {{-- Registrant name --
            <div style="text-align: center; font-size: 1.25rem; font-weight: bold;">
                {{ $registrant->student->person->fullNameAlpha() }}
            </div>
            <div style="text-align: center; font-size: 0.8rem;">
                {{ $registrant->student->emails->count() ? $registrant->student->emails->first()->email : 'No email found' }}
            </div>
            <div style="text-align: center; font-size: 0.8rem;">
                {{ $registrant->timeslot }}
            </div>
            <div style="text-align: center; font-size: 0.8rem;">
                {{ $registrant->student->currentSchool->shortName }}
            </div>

        </div>

        {{-- RIGHT BOX --}}
        <div style="border: 1px solid black; padding: .25rem; margin: .25rem; display: flex; flex-direction: row;">
            {{-- LEFT SIDE OF RIGHT BOX --}}
            <div style="margin-right: 1rem;">
                {{-- Header --
                <div>
                    {{ $eventversion->name }}
                </div>
                <div style="font-weight: bold; text-transform: uppercase;">
                    {{ isset($rooms[1]) ? $rooms[1]->descr : 'No room found' }}
                </div>

                {{-- Aud# + Instrumentation --
                <div style="display: flex; ">
                    <div style="display:flex; margin-right: 1rem;">
                        <label>Aud #</label>
                        <div class="data">{{ $registrant->id }}</div>
                    </div>
                    <div style="display: flex;">
                        <label></label>
                        <div class="data">{{ $instrumentation->formattedDescr() }}</div>
                    </div>
                </div>

                {{-- Registrant name --
                <div style="text-align: center; font-size: 1.25rem; font-weight: bold;">
                    {{ $registrant->student->person->fullNameAlpha() }}
                </div>
                <div style="text-align: center; font-size: 0.8rem;">
                    {{ $registrant->student->emails->count() ? $registrant->student->emails->first()->email : 'No email found' }}
                </div>
                <div style="text-align: center; font-size: 0.8rem;">
                    timeslot
                </div>
                <div style="text-align: center; font-size: 0.8rem;">
                    {{ $registrant->student->currentSchool->shortName }}
                </div>
            </div>
            {{-- RIGHT SIDE OF RIGHT BOX --
            <div style="text-align: center; color: darkgrey;">
                <div style="border: 1px solid black; border-bottom: 0; padding:0 0.5rem;">TAB</div>
                <div style="border: 1px solid black; border-bottom: 0; border-top: 0; padding:0 0.5rem;">ROOM</div>
                <div style="border: 1px solid black; border-top: 0; padding:0 0.5rem;">ONLY</div>
            </div>


        </div>
    </div>
@endforeach
--}} -->
