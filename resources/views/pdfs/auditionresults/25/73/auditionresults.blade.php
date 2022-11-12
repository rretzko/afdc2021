<div style="display: none;">{{ set_time_limit(360) }}</div>
<style>
    table{border-collapse: collapse;margin:auto;}
    td,th{border: 1px solid black; text-align: center; padding:0 .25rem; font-size: .66rem;}

    .page_break{page-break-before: always;}
</style>

<div style="text-align: center; font-size: 1.5rem;">
    Audition Results for the {{ $eventversion->name }}
</div>

@foreach($registrants AS $key => $voicings)

    <h3>{{ strtoupper($key) }}</h3>

    <table>
        <thead>
            <tr>
                <th colspan="3" style="border-top: 0; border-left: 0;"></th>
                @for($i = 1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                    <th colspan="{{ $scoringcomponents->count() }}" style="border-right: 1px solid black;">
                        Judge #{{ $i }}
                    </th>
                @endfor
            </tr>
            <tr>
                <th colspan="3" style="border-top: 0; border-left: 0;"></th>
                @for($i=1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                    @foreach($eventversion->filecontenttypes AS $filecontenttype)
                        <th colspan="{{$filecontenttype->scoringcomponents->where('eventversion_id', $eventversion->id)->count()}}">{{ ucwords($filecontenttype->descr) }}</th>
                    @endforeach
                @endfor
                <th colspan="3" style="border-top:0; border-right: 0;"></th>
            </tr>
            <tr>
                <th>###</th>
                <th>Reg.Id</th>
                <th>Voice</th>
                @for($i = 1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                    @foreach($scoringcomponents AS $scoringcomponent)
                        <th>{{ $scoringcomponent->abbr }}</th>
                    @endforeach
                @endfor
                <th>Total</th>
                @foreach($eventversion->event->eventensembles AS $eventensemble)
                    @if($eventensemble->instrumentations()->contains($voicings->first()->instrumentations->first()))
                        <th>{{ ucwords($eventensemble->acceptance_abbr) }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($voicings AS $registrant)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $registrant->id }}</td>
                    <td>{{ strtoupper($registrant->instrumentations->first()->abbr)  }}</td>

                    @foreach($score->registrantScores($registrant) AS $value)
                        <td>{{ $value }}</td>
                    @endforeach

                    <td>
                        {{ $scoresummary->registrantScore($registrant) }}
                    </td>


                    @foreach($eventversion->event->eventensembles AS $eventensemble)
                        @if($eventensemble->instrumentations()->contains($registrant->instrumentations->first()))
                            <td>
                                @if($scoresummary->registrantResult($registrant) == $eventensemble->acceptance_abbr)
                                    {{ $scoresummary->registrantResult($registrant) }}
                                @elseif($scoresummary->registrantResult($registrant) == 'n/a')
                                    n/a
                                @elseif($scoresummary->registrantResult($registrant) == 'inc')
                                    inc
                                @elseif(! $scoresummary->registrantScore($registrant))
                                    n/s
                                @else
                                    -
                                @endif
                            </td>
                        @endif
                    @endforeach

                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PAGE BREAK --}}
    <div class="page_break"></div>

@endforeach


