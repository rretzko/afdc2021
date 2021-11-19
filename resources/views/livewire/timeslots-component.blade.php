<style>
    table{border-collapse: collapse;margin-top: 1rem;}
    td,th{border:1px solid black; padding: 0 .25rem;}
</style>
<div>
    <table>
        <thead>
            <tr>
                <th>Timeslot</th>
                <th>School</th>
                <th>Total</th>
                @foreach($eventversion->instrumentations() AS $instrumentation)
                    <th>{{ strtoupper($instrumentation->abbr) }}</th>
                @endforeach
                <th></th>
            </tr>
        </thead>
        @foreach($schools AS $school)
            <form method="post" action="" >
            <tr>
                <td>
                    <input type="text" name="timeslots" id="{{ $school->id }}" style="width: 4rem;" />
                </td>
                <td>{{ $school->shortName }}</td>
                <td style="text-align: center;">{{ $eventversion->registrantsForSchool($school)->count() }}</td>
                @foreach($eventversion->instrumentations() AS $instrumentation)
                    <td style="text-align: center;">
                        {{ $eventversion->registrantsForSchoolByInstrumentation($school, $instrumentation)->count() }}
                    </td>
                @endforeach
                <td>
                    <input type="submit" value="Update" />
                </td>
            </tr>
            </form>
        @endforeach
    </table>
</div>
