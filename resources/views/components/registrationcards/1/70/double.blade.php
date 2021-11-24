@props([
    'eventversion',
    'instrumentation',
    'registrant',
    'rooms',
])

<style>
    label{margin-right: .5rem;}
    .data{font-weight: bold;}
</style>
<div style="display:flex; flex-direction:row;">
    {{-- LEFT BOX --}}
    <div style="border: 1px solid black; padding: .25rem; margin: .25rem;">
        {{-- Header --}}
        <div>
            {{ $eventversion->name }}
        </div>
        <div style="font-weight: bold; text-transform: uppercase;">
            {{ isset($rooms[0]) ? $rooms[0]->descr : 'No room found' }}
        </div>

        {{-- Aud# + Instrumentation --}}
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

        {{-- Registrant name --}}
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
            {{-- Header --}}
            <div>
                {{ $eventversion->name }}
            </div>
            <div style="font-weight: bold; text-transform: uppercase;">
                {{ isset($rooms[1]) ? $rooms[1]->descr : 'No room found' }}
            </div>

            {{-- Aud# + Instrumentation --}}
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

            {{-- Registrant name --}}
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
        {{-- RIGHT SIDE OF RIGHT BOX --}}
        <div style="text-align: center; color: darkgrey;">
            <div style="border: 1px solid black; border-bottom: 0; padding:0 0.5rem;">TAB</div>
            <div style="border: 1px solid black; border-bottom: 0; border-top: 0; padding:0 0.5rem;">ROOM</div>
            <div style="border: 1px solid black; border-top: 0; padding:0 0.5rem;">ONLY</div>
        </div>


    </div>
</div>
