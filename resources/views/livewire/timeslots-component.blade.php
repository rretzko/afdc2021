<div style="margin-bottom: -1rem;">
    @if(config('app.url') === 'http://afdc2021.test')
        <div style="display: flex; justify-content: space-between;">
            <a href="{{ route('registrationmanagers.timeslotassignment.download',['eventversion' => $eventversion]) }}">
                Download Assigned Timeslots
            </a>

            <a href="{{ route('registrationmanagers.timeslotstudent.index',['eventversion' => $eventversion]) }}">
                Student Detail
            </a>
        </div>
    @else
        <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/timeslots/download/{{ $eventversion->id }}">
            Download Assigned Timeslots
        </a>

        <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/timeslots/students/{{ $eventversion->id }}">
            Student Detail
        </a>
    @endif

</div>
<div>
    {!! $table !!}
</div>
