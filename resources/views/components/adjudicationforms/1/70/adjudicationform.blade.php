@props([
    'eventversion',
    'instrumentation',
    'registrants',
    'room',
])
<div style="border: 1px solid black; padding: .25rem; margin: .25rem;">

    <div style="text-align: center; font-weight: bold;">
        {{ $eventversion->name }}
    </div>

    <div style="text-align: center; font-weight: bold; background-color: rgba(0,0,0,.1); margin-bottom: .5rem; border-bottom: 1px solid black; border-top: 1px solid black;">
        {{ $room->descr }}
    </div>

    {{-- LEGEND --}}
    <div style="display:flex; flex-direction: row; justify-content: space-around; border: 1px solid black; margin-bottom: .5rem;">
            <div>
                1 - Highly Superior
            </div>
            <div style="background-color: rgba(0,0,0,.2); width: 3rem; text-align: center;">
                2
            </div>
            <div>
                3 - Excellent
            </div>
            <div style="background-color: rgba(0,0,0,.2); width: 3rem; text-align: center;">
                4
            </div>
            <div>
                5 - Good
            </div>
            <div style="background-color: rgba(0,0,0,.2); width: 3rem; text-align: center;">
                6
            </div>
            <div>
                7 - Fair
            </div>
            <div style="background-color: rgba(0,0,0,.2); width: 3rem; text-align: center;">
                8
            </div>
            <div>
                9 - Poor
            </div>
        </div>

    <div style="">
        <style>
            table{border-collapse: collapse; width: available;margin: auto; }
            td,th{border: 1px solid black; padding: 0 .25rem; text-align: center;}
        </style>
        <table>
            <thead>
            <tr>
                <th colspan="3"></th>
                @foreach($room->filecontenttypes AS $filecontenttype)
                    <th colspan="{{ $filecontenttype->scoringcomponents->where('eventversion_id',$eventversion->id)->count() }}">{{ $filecontenttype->descr }}</th>
                @endforeach
                <th></th>
            </tr>
            <tr>
                <th>###</th>
                <th>Timeslot</th>
                <th>Reg.Id</th>
                @foreach($room->filecontenttypes AS $filecontenttype)
                    @foreach($filecontenttype->scoringcomponents->where('eventversion_id',$eventversion->id) AS $scoringcomponent)
                        <th>{{ $scoringcomponent->abbr }}</th>
                    @endforeach
                @endforeach
                <th>Total</th>
            </tr>
            </thead>

            <tbody>
            @foreach($registrants AS $registrant)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $registrant->timeslot }}</td>
                    <td>{{ $registrant->id }}</td>
                    @foreach($room->filecontenttypes AS $filecontenttype)
                        @foreach($filecontenttype->scoringcomponents->where('eventversion_id', $eventversion->id) AS $scoringcomponent)
                            <th></th>
                        @endforeach
                    @endforeach
                    <td></td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

</div>
<!-- {{--
        <div style="font-weight: bold; text-transform: uppercase;">
            {{ ($room) ? $room->descr : 'No room found' }}
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

    {{-- RIGHT SIDE OF RIGHT BOX
    <div style="text-align: center; color: darkgrey;">
        <div style="border: 1px solid black; border-bottom: 0; padding:0 0.5rem;">TAB</div>
        <div style="border: 1px solid black; border-bottom: 0; border-top: 0; padding:0 0.5rem;">ROOM</div>
        <div style="border: 1px solid black; border-top: 0; padding:0 0.5rem;">ONLY</div>
    </div>

    </div>
</div>
--}} -->
