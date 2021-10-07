<div>
    <style>
        td,th{border: 1px solid black; padding:0 .25rem;}
        ul{list-style-type: none; margin-left: -2rem;}
    </style>
    <table>
        <thead>
            <tr>
                <th>###</th>
                <th>Room</th>
                <th>Content</th>
                <th>Voice Part(s)</th>
                <th class="sr-only">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms AS $room)
                <tr style="background-color: lightgoldenrodyellow;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $room->descr }}</td>
                    <td>
                        @foreach($room->filecontenttypes AS $filecontenttype)
                            {{ $filecontenttype->descr }}
                        @endforeach
                    </td>
                    <td>
                        @foreach($room->instrumentations AS $instrumentations)
                            {{ $instrumentations->descr }}
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <td colspan="4" style="padding-left: 3rem;">
                        <ul>
                            @foreach($room->adjudicators AS $adjudicator)
                            <li>
                                {{ $adjudicator->adjudicatorname }}
                            </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

