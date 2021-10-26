<div style="margin: auto;">
    <style>
        th{text-align: center;}
        td,th{border: 1px solid black; padding: 0 .25rem;}
    </style>
    <h3 style="text-align: center;">
        {{ $school->name }}
    </h3>

    <div id="changeform" style="width: 100%;">
        @if($registrant)
            <form method="POST" action="{{ route('registrationmanager.registrant.update',['registrant' => $registrant]) }}" style="display: flex; flex-direction: column; width: 50%; margin-left: 25%; margin-bottom: 1rem; border:1px solid gray; padding: .25rem;">
                @csrf
                <header>Click the student's name to populate this form...</header>
                <div style="display: flex; flex-direction: row; justify-content: space-around;">
                    <div>
                        <label>Name</label>
                        <div>
                            {{ $registrant->student->person->fullName() }}
                        </div>
                    </div>
                    <div>
                        <label>Voice Part</label></lable>
                        <div>
                            {!! $voiceparts !!}
                        </div>
                    </div>
                    <div>
                        <label></label>
                        <div>
                            <input type="submit" name="submit" id="submit" value="Update" />
                        </div>
                    </div>

                </div>
            </form>
        @endif
    </div>

    <table style="margin:auto;">
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
               <td>
                    <a href="{{ route('registrants.school.edit',
                        [
                            'eventversion' => $eventversion,
                            'school' => $school,
                            'registrant' => $registrant,
                        ]) }}"
                    >
                        {{ $registrant->student->person->fullNameAlpha() }}
                    </a>
                </td>
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
