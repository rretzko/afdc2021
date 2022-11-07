<div style="margin: auto;">
    <x-eventadministration.tablestyle />

    <table>
        <thead>
            <tr>
                <td colspan="5" style="text-align: right; padding-right: 1rem; font-size: smaller;border: 1px solid white; border-bottom: 1px solid black;">
                    <a href="{{ route('eventadministrator.acknowledgedteachers.download',['eventversion'=> $eventversion]) }}">
                        Download
                    </a>
                </td>
            </tr>
            <tr>
                <th>###</th>
                <th>Name</th>
                <th>School</th>
                <th>Emails</th>
                <th>Phones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers AS $teacher)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $teacher->person->fullnameAlpha() }}</td>
                    <td>{{ $teacher->currentSchool()->name }}</td>
                    <td>
                        @foreach($teacher->person->subscriberemails AS $email)
                            <div>
                                <a href="mailto:{{ $email->email }}?subject={{ $eventversion->name }}&body=Hi, {{ $teacher->person->first }}">
                                    {{ $email->email }}
                                </a>
                            </div>
                        @endforeach
                    </td>

                    <td>
                        @foreach($teacher->person->phones AS $phone)
                            <div>
                                {{ $phone->labeledPhone }}
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
