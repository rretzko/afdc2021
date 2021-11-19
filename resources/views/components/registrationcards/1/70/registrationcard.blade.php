@props([
    'eventversion',
    'instrumentation',
    'registrant',
])
<style>
    label{margin-right: .5rem;}
    .data{font-weight: bold;}
</style>
<div style="border: 1px solid black; padding: .25rem; margin: .25rem;">
    {{-- Header --}}
    <div>
        {{ $eventversion->name }}
    </div>
    <div>
        Room Name
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
