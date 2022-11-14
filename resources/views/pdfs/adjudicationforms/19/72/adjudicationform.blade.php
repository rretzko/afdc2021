<style>
    table{width: 100%; border-collapse: collapse; padding: .25rem;}
    td,th{border: 1px solid black;  text-align: center;}
    .page_break{page-break-before: always;}
</style>

@foreach($rooms AS $room)

    {{-- HEADERS --}}
    <table>
        <tr>
            <th style="font-weight: bold;">
                {{ $eventversion->name }}
            </th>
        </tr>
        <tr style="background-color: rgba(0,0,0,.1);">
            <th style="font-weight: bold;">
                {{ $room->descr }}
            </th>
        </tr>
    </table>

    {{-- LEGEND --}}
    <table>
        <tr>
            <td>
                5 - Superior
            </td>
            <td style="background-color: rgba(0,0,0,.2); text-align: center;">
                4 - Excellent
            </td>
            <td>
                3 - Good
            </td>
            <td style="background-color: rgba(0,0,0,.2);text-align: center;">
                2 - Fair
            </td>
            <td>
                1 - Poor
            </td>
        </tr>
    </table>

    {{-- ADJUDICATORS --}}
    <table>
        <tbody>
        <tr>
        <th>Adjudicators</th>
        @foreach($room->adjudicators AS $adjudicator)
            <td>{{ $adjudicator->adjudicatorName }} ({{ $adjudicator->user_id }})</td>
        @endforeach
        </tr>
        </tbody>
    </table>

    {{-- ROWS --}}
    <table>
        <thead>
        <tr>
            <th colspan="3"></th>
            @foreach($room->filecontenttypes AS $filecontenttype)
                <th colspan="{{ $filecontenttype->scoringcomponents->where('eventversion_id',$eventversion->id)->count() }}">{{ $filecontenttype->descr }}</th>
            @endforeach
            <th></th>
        </tr>
        <tr>
            <th>###</th>
            <th>Timeslot</th>
            <th>Reg.Id</th>
            @foreach($room->filecontenttypes AS $filecontenttype)
                @foreach($filecontenttype->scoringcomponents->where('eventversion_id',$eventversion->id) AS $scoringcomponent)
                    <th>
                        {{ $scoringcomponent->abbr }}
                        @if($scoringcomponent->abbr == 'Qrt')<span style="font-size: smaller;">( * 4 )</span>@endif
                    </th>
                @endforeach
            @endforeach
            <th>Total</th>
        </tr>
        </thead>

        <tbody>
        {{-- @foreach($registrants AS $registrant) --}}
        @foreach($room->auditioneesByTime() AS $registrant)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $registrant->id }}</td>
                <td>{{ $registrant->timeslot }}</td>
                @foreach($room->filecontenttypes AS $filecontenttype)
                    @foreach($filecontenttype->scoringcomponents->where('eventversion_id', $eventversion->id) AS $scoringcomponent)
                        <th></th>
                    @endforeach
                @endforeach
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="page_break"></div>
@endforeach
