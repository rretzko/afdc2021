@props([
    'eventversion' => $eventversion,
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

    <div class="scoresheet" style=" margin-bottom: 1rem;">
        <style>
            table{ border-collapse: collapse; margin: auto;}
            td,th{border: 1px solid black;text-align: center;}
            .solo{background-color: rgba(0,0,0,0.1);}
        </style>
        <table>
            <thead>
            {{-- THEAD 1: CATEGORY --}}
            <tr>
                <th colspan="@if($eventversion->eventversionconfig->virtualaudition) 2 @else 3 @endif"></th>
                @foreach($room->filecontenttypes AS $filecontenttype)
                    <th colspan="{{ $filecontenttype->scoringcomponents->where('eventversion_id', $room->eventversion_id)->count() }}"
                        class="{{ $filecontenttype->descr }}"
                    >
                        {{ $filecontenttype->descr }}
                    </th>
                @endforeach
                <th></th>
            </tr>
            {{-- THEAD 2: COMPONENT --}}
            <tr>
                <th>###</th>
                @if(! $eventversion->eventversionconfig->virtualaudition)
                    <th>Timeslot</th>
                @endif
                <th>Registrant #</th>
                @foreach($room->filecontenttypes AS $filecontenttype)
                    @foreach($filecontenttype->scoringcomponents->where('eventversion_id',$room->eventversion_id) AS $scoringcomponent)
                        <th style="width: 3rem;" class="{{ $filecontenttype->descr }}" >
                            {{ $scoringcomponent->abbr }}
                            @if($scoringcomponent->abbr === 'Qrt')<span style="font-size: smaller;">( * 4 )</span>@endif
                        </th>
                    @endforeach
                @endforeach
                <th style="width: 5rem;">Total</th>
            </tr>
            </thead>
            <tbody>
            @forelse($room->auditioneesByTime() AS $auditionee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    @if(! $eventversion->eventversionconfig->virtualaudition)
                        <td>{{ $auditionee->timeslot }}</td>
                    @endif
                    <td>{{ $auditionee->id }}</td>
                    @foreach($room->filecontenttypes AS $filecontenttype)
                        @for($i=0; $i<$filecontenttype->scoringcomponents->where('eventversion_id',$room->eventversion_id)->count();$i++)
                            <td class="{{ $filecontenttype->descr }}"></td>
                        @endfor
                    @endforeach
                    {{-- TOTAL COLUMN --}}
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="@if($eventversion->eventversionconfig->virtualaudition) 7 @else 8 @endif">
                        No registrants found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </div>

</div>
