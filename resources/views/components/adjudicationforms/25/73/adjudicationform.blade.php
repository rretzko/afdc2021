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
    <div style="display:flex; flex-direction: row; justify-content: space-evenly;  border: 1px solid black; margin-bottom: .5rem;">
        <style>
            .rating{background-color: lavender; padding: 0 1rem;}
        </style>
            <div class="rating">
                1-2 Superior
            </div>

            <div class="rating">
                3-4 Excellent
            </div>

            <div class="rating">
                5-6 Good
            </div>
            <div class="rating">
                7-8 Fair
            </div>
            <div class="rating">
                9 Poor
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
                <th colspan="2"></th>
                <th colspan="5">Scales</th>
                <th colspan="4">Solo</th>
                <th></th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <th colspan="2">Low Scale</th>
                <th colspan="3">High Scale</th>
                <th colspan="4">Solo</th>
                <th style="border-bottom: 1px solid white;">Grand</th>
            </tr>
            <tr>
                <th>###</th>
                <th>Reg.Id</th>
                <th>Quality</th>
                <th>Intonation</th>
                <th>Quality</th>
                <th>Intonation</th>
                <th>Sub1</th>
                <th>Quality</th>
                <th>Intonation</th>
                <th>Musicianship</th>
                <th>Sub2</th>
                <th>Total</th>
            </tr>
            </thead>

            <tbody>
            @foreach($registrants AS $registrant)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $registrant->id }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
