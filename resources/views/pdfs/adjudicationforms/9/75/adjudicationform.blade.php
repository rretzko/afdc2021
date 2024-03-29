<style>
    table{width: 100%; border-collapse: collapse; padding: .25rem;}
    td,th{border: 1px solid black;  text-align: center;}
    .page_break{page-break-before: always;}
    .solo{background-color: rgba(0,0,0,0.1);}
</style>

    {{-- HEADERS --}}
    <table>
        <tr>
            <th style="font-weight: bold;">
                {{ $eventversion->name }}
            </th>
        </tr>
        <tr style="background-color: rgba(0,0,0,.1);">
            <th style="font-weight: bold; font-size: 2rem;">
                {{ $room->descr }}: Tolerance: {{ $room->tolerance }} points
            </th>
        </tr>
    </table>

    {{-- LEGEND --}}
    <table>
        <tr>
            <td>
                1 - Highly Superior
            </td>
            <td style="background-color: rgba(0,0,0,.2); width: 3rem; text-align: center;">
                2
            </td>
            <td>
                3 - Excellent
            </td>
            <td style="background-color: rgba(0,0,0,.2); width: 3rem; text-align: center;">
                4
            </td>
            <td>
                5 - Good
            </td>
            <td style="background-color: rgba(0,0,0,.2); width: 3rem; text-align: center;">
                6
            </td>
            <td>
                7 - Fair
            </td>
            <td style="background-color: rgba(0,0,0,.2); width: 3rem; text-align: center;">
                8
            </td>
            <td>
                9 - Poor
            </td>
        </tr>
    </table>

{{-- JUDGES --}}
<table>
    <tr>
        @foreach($room->adjudicators AS $adjudicator)
            <td>{{ $adjudicator->adjudicatorname }} ({{ $adjudicator->user_id }})</td>
        @endforeach
    </tr>
</table>

    {{-- ROWS --}}
    <table>
        <thead>
        <tr>
            <th colspan="@if($eventversion->eventversionconfig->virtualaudition) 2 @else 3 @endif"></th>
            @foreach($room->filecontenttypes AS $filecontenttype)
                <th colspan="{{ $filecontenttype->scoringcomponents->where('eventversion_id',$eventversion->id)->count() }}"
                class="{{ $filecontenttype->descr }}"
                >
                    {{ $filecontenttype->descr }}
                </th>
            @endforeach
            <th></th>
        </tr>
        <tr>
            <th>###</th>
            @if(! $eventversion->eventversionconfig->virtualaudition)
                <th>Timeslot</th>
            @endif
            <th>Reg.Id</th>
            @foreach($room->filecontenttypes AS $filecontenttype)
                @foreach($filecontenttype->scoringcomponents->where('eventversion_id',$eventversion->id) AS $scoringcomponent)
                    <th class="{{ $filecontenttype->descr }}">
                        {{ $scoringcomponent->abbr }}
                    </th>
                @endforeach
            @endforeach
            <th>Total</th>
        </tr>
        </thead>

        <tbody>
        @foreach($registrants AS $registrant)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @if(! $eventversion->eventversionconfig->virtualaudition)
                    <td>{{ $registrant->timeslot }}</td>
                @endif
                <td>{{ $registrant->id }}</td>
                @foreach($room->filecontenttypes AS $filecontenttype)
                    @foreach($filecontenttype->scoringcomponents->where('eventversion_id', $eventversion->id) AS $scoringcomponent)
                        <th class="{{$filecontenttype->descr}}"></th>
                    @endforeach
                @endforeach
                <td></td>
            </tr>
        @endforeach

        {{-- ADD 10 EXTRA ROWS FOR WALK-INS --}}
        @for($i=0; $i<10; $i++)
            <tr>
                <td style="height: 1rem;"></td>
                @if(! $eventversion->eventversionconfig->virtualaudition )
                    <td></td>
                @endif
                <td></td>
                @foreach($room->filecontenttypes AS $filecontenttype)
                    @foreach($filecontenttype->scoringcomponents->where('eventversion_id', $eventversion->id) AS $scoringcomponent)
                        <th class="{{ $filecontenttype->descr }}"></th>
                    @endforeach
                @endforeach
                <td></td>
            </tr>
        @endfor
        </tbody>
    </table>

    <div class="page_break"></div>

