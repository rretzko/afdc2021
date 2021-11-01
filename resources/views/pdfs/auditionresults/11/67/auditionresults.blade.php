<div style="display: none;">{{ set_time_limit(360) }}</div>
<style>
    table{border-collapse: collapse;margin:auto;}
    td,th{border: 1px solid black; text-align: center; padding:0 .25rem; font-size: .6rem;}

    .page_break{page-break-before: always;}
</style>

<div style="text-align: center; font-size: 1.5rem;">
    Audition Results for the {{ $eventversion->name }}
</div>
{{dd(phpinfo())}}
@foreach($registrants AS $key => $voicings)
<!-- {{-- @if(($key === 'soprano i') || ($key === 'soprano ii') || ($key === 'alto i') || ($key === 'alto ii') || ($key === 'bass'))
@if(($key === 'soprano i') || ($key === 'soprano ii') || ($key === 'alto i') || ($key === 'alto ii') || ($key === 'tenor') || ($key === 'bass')) --}} -->
@if($key === 'bass')
    <h3>{{ strtoupper($key) }}</h3>

    <table>
        <thead>
            <tr>
                <th colspan="3" style="border: 0; border-right: 1px solid black;"></th>
                @for($i = 1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                    <th colspan="{{ $scoringcomponents->count() }}" style="border-right: 1px solid black;">
                        Judge #{{ $i }}
                    </th>
                @endfor
                <th colspan="2" style="border: 0; border-left: 1px solid black;"></th>
            </tr>
            <tr>
                <th colspan="3" style="border-top: 0; border-left: 0;"></th>
                @for($i=1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                    <th colspan="4">Scales</th>
                    <th colspan="3">Solo</th>
                    <th colspan="3">Swan</th>
                @endfor
                <th colspan="2" style="border:0; border-left:1px solid black;"></th>
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
                <th>Mix</th>
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
                    <td style="border-right: 1px solid black;">
                        {{ $scoresummary->registrantResult($registrant) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PAGE BREAK --}}
    <div class="page_break"></div>
@endif
@endforeach


