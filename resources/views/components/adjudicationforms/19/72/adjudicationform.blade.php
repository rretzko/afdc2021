@props([
    'room' => $room,
])

<div>

    <div class="headers" style="display: flex; flex-direction: row; margin-bottom: 1rem;">

        <h4 style="margin-right: 2rem;">Adjudicators</h4>
        @forelse($room->adjudicators AS $adjudicator)
            <div style="margin-right: 2rem; margin-top: 0.25rem; ">
                {{ $adjudicator->person->fullnameAlpha() }} ({{ $adjudicator->user_id }})
            </div>
        @empty
            No adjudicators found
        @endforelse

    </div>

    <div class="scoresheet" style="margin: auto; margin-bottom: 1rem;">
        <style>
            table{ border-collapse: collapse; border: 1px solid black;}
            td,th{border: 1px solid black;text-align: center;}
        </style>
        <table>
            <thead>
            <tr>
                <th colspan="3"></th>
                @foreach($room->filecontenttypes AS $filecontenttype)
                    <th colspan="{{ $filecontenttype->scoringcomponents->where('eventversion_id', $room->eventversion_id)->count() }}">
                        {{ $filecontenttype->descr }}
                    </th>
                @endforeach
                <th></th>
            </tr>
            <tr>
                <th>###</th>
                <th>Timeslot</th>
                <th>Registrant #</th>
                @foreach($room->filecontenttypes AS $filecontenttype)
                    @foreach($filecontenttype->scoringcomponents->where('eventversion_id',$room->eventversion_id) AS $scoringcomponent)
                        <th>
                            {{ $scoringcomponent->abbr }}
                            @if($scoringcomponent->abbr === 'Qrt')<span style="font-size: smaller;">( * 4 )</span>@endif
                        </th>
                    @endforeach
                @endforeach
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @forelse($room->auditioneesByTime() AS $auditionee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $auditionee->timeslot }}</td>
                    <td>{{ $auditionee->id }}</td>
                    @foreach($room->filecontenttypes AS $filecontentype)
                        @for($i=0; $i<=$filecontenttype->scoringcomponents->where('eventversion_id',$room->eventversion_id)->count();$i++)
                            <td></td>
                        @endfor
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="8">No registrants found</tr>
            @endforelse
            </tbody>
        </table>

    </div>

</div>
