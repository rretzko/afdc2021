<div style="margin-bottom: -1rem;">
    @if(config('app.url') === 'http://afdc2021.test')
        <a href="{{ route('registrationmanagers.timeslotassignment.download',['eventversion' => $eventversion]) }}">
            Download Assigned Timeslots
        </a>
    @else
        <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/timeslots/download/{{ $eventversion->id }}">
            Download Assigned Timeslots
        </a>
    @endif

</div>
<div>
    {!! $table !!}
</div>
