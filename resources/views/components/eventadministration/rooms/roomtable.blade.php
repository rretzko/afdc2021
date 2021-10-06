<div>
    <style>
        td,th{border: 1px solid black; padding:0 .25rem;}
        ul{list-style-type: none; margin-left: -2rem;}
    </style>
    <table>
        <thead>
            <tr>
                <th>###</th>
                <th>Name</th>
                <th>Content</th>
                <th>Voice Part(s)</th>
                <th>Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms AS $room)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if(config('app.url') === 'http://afdc2021.test')
                            <a href="{{ route('eventadministrator.rooms.edit', ['room' => $room]) }}">
                                {{ $room->descr }}
                            </a>
                        @else
                            <a href="https://afdc-2021-l28q8.ondigitalocean.com">
                                {{ $room->descr }}
                            </a>
                        @endif
                    </td>
                    <td>
                        <ul>
                            @foreach($room->filecontenttypes AS $filecontenttype)
                                <li>{{ $filecontenttype->descr }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul>
                            @foreach($room->instrumentations AS $instrumentation)
                                <li>{{ $instrumentation->descr }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td style="text-align: center;">
                        {{ $room->order_by }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
