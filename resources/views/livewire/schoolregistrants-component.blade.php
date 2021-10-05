<div style="margin: auto;">
    <style>
        th{text-align: center;}
        td,th{border: 1px solid black; padding: 0 .25rem;}
    </style>
    <h3 style="text-align: center;">
        {{ $school->name }}
    </h3>
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
                    <input wire:click="updateReviewed({{ $registrant->id }})"
                           type="checkbox"
                           name="reviewed"
                           id="reviewed"
                           value="1"
                           key="{{ $registrant->id }}"
                           {{ in_array($registrant->id, $reviews) ? 'CHECKED' : '' }}
                    />
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
