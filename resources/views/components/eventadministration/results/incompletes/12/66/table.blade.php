@props([
'eventversion',
'incompletes'
])
<div style="margin-top: 1rem;">
    <header style="font-weight: bold; text-align: center; width: 100%;">
        {{ $eventversion->name }} Incomplete Audition Results
    </header>
    <x-eventadministration.tablestyle />
    <table style="margin: auto;">
        <thead>
        <tr>
            <th></th>
            @for($i=1; $i<=$eventversion->eventversionconfig->judge_count; $i++ )
                <th
                    style="text-align:center"
                    colspan="{{ $eventversion->scoringcomponents->count() }}">Judge #{{ $i }}
                </th>
            @endfor
        </tr>
        <tr>
            <th></th>
            @for($i=1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                @foreach($eventversion->filecontenttypes AS $filecontenttype)
                    <th
                        style="text-align:center"
                        colspan="{{ $eventversion->filecontenttypeScoringcomponents($filecontenttype)->count() }}"
                        >
                        {{ ucwords($filecontenttype->descr) }}
                    </th>
                @endforeach
            @endfor
        </tr>
        <tr>
            <th>Reg.Id</th>
            @for($i=1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                @foreach($eventversion->filecontenttypes AS $filecontenttype)
                    @foreach($eventversion->filecontenttypeScoringcomponents($filecontenttype) AS $scoringcomponent)
                        <th style="text-align:center" >
                            {{ strtoupper($scoringcomponent->abbr )}}
                        </th>
                    @endforeach
                @endforeach
            @endfor
        </tr>
        </thead>
        <tbody>
        @foreach($incompletes AS $scoresummary)
            <tr>
                <td title="{{ $scoresummary['registrant']->student->person->fullNameAlpha() }},{{$scoresummary['registrant']->student->currentSchool->name}}">
                    {{$scoresummary['registrant']->id}}
                </td>
                @foreach($scoresummary->reportingDetails() AS $score)
                    <td style="text-align:center; @if(! $score) color: lightgrey; @endif" >
                        {{ $score}}
                    </td>
                @endforeach
                <td style="text-align: right;">{{ $scoresummary->score_total }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
