@props([
'eventversion',
'registrants'
])
<style>
    th{text-align: center;}
    td,th{border: 1px solid black;}
</style>
<div style="margin: auto;">
    <table>
        <thead>
            <tr>
                <th>###</th>
                <th>Student</th>
                <th>Voice</th>
                @foreach($eventversion->filecontenttypes AS $filecontenttype)
                    <th>{{ ucwords($filecontenttype->descr) }}</th>
                @endforeach
                <th>Rev'd</th>
            </tr>
        </thead>
        <tbody>
        @foreach($registrants AS $registrant)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $registrant->student->person->fullNameAlpha() }}</td>
                <td style="text-align: center;">
                    @foreach($registrant->instrumentations AS $instrumentation)
                        {{ strtoupper($instrumentation->abbr) }}
                    @endforeach
                </td>
                @foreach($eventversion->filecontenttypes AS $filecontenttype)
                    <td >{!! $registrant->fileviewport($filecontenttype) !!}</td>
                @endforeach
                <td style="text-align: center;">
                    <input type="checkbox" name="reviewed" id="reviewed" value="1" onclick="reviewed({{ $registrant->id }})" />
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
<script>
    function reviewed($registrant_id)
    {
        alert($registrant_id+' reviewed');
    }
</script>

</div>
