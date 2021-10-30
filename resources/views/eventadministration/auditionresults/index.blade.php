@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Audition Results: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section id="header" style="padding: 1rem;">
                        <div class="input-group" style="display: flex; flex-direction: row; justify-content: space-around;">
                            <label for="instrumentation_id"></label>

                                @foreach($eventversion->instrumentations() AS $instrumentation)
                                    <a href="{{ route('eventadministrator.tabroom.results.show',
                                            [
                                                'eventversion' => $eventversion,
                                                'instrumentation' => $instrumentation
                                            ]
                                        )}}"
                                    >
                                        {{ strtoupper($instrumentation->descr) }}
                                    </a>
                                @endforeach

                        </div>
                    </section>

                    <section id="results">
                        @if(isset($completes))
                            <div style="padding: 0 1rem; margin:auto;">
                                <style>
                                    td,th{border: 1px solid black;padding:0 .25rem; text-align: center;}
                                </style>
                                <table>
                                <thead>
                                    <tr>
                                        <th colspan="3" style="border-top: 0; border-bottom: 0; border-left: 0;"></th>
                                        @for($i = 1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                                            <th colspan="{{ $scoringcomponents->count() }}" >
                                                Judge #{{ $i }}
                                            </th>
                                        @endfor
                                        <th colspan="3" style="border:0; border-left: 1px solid black;"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" style="border-top: 0; border-left: 0;"></th>
                                        @for($i=1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                                                <th colspan="4">Scales</th>
                                                <th colspan="3">Solo</th>
                                                <th colspan="3">Swan</th>
                                        @endfor
                                        <th colspan="3" style="border-top:0; border-right: 0;"></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;">###</th>
                                        <th>Reg.Id</th>
                                        <th>Voice</th>
                                        @for($i = 1; $i<=$eventversion->eventversionconfig->judge_count; $i++)
                                            @foreach($scoringcomponents AS $scoringcomponent)
                                                <th>{{ $scoringcomponent->abbr }}</th>
                                            @endforeach
                                        @endfor
                                        <th>Total</th>

                                        <th>Mix</th>
                                        @if($eventversion->event->eventensembles->count() === 2)
                                            <th>Tbl</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($registrants AS $registrant)
                                    <tr>
                                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                                        <td style="text-align: center;">
                                            <span title="{{ $registrant->student->person->fullnameAlpha() }} @ {{ $registrant->student->currentSchool->shortname }}">
                                                {{ $registrant->id }}
                                            </span>
                                        </td>
                                        <td style="text-align: center;">{{ strtoupper($registrant->instrumentations->first()->abbr) }}</td>

                                            @foreach($score->registrantScores($registrant) AS $value)
                                                <td>{{ $value }}</td>
                                            @endforeach

                                        <td>
                                            {{ $scoresummary->registrantScore($registrant) }}
                                        </td>

                                        <td>
                                            @if(
                                                ($eventversion->event->eventensembles[0]->acceptanceStatus($eventversion, $registrant) === 'TB') ||
                                                ($eventversion->event->eventensembles[1]->acceptanceStatus($eventversion, $registrant) === 'TB')
                                                )
                                                -
                                            @else
                                                {{ $eventversion->event->eventensembles[0]->acceptanceStatus($eventversion, $registrant) }}
                                            @endif
                                        </td>

                                        @if($eventversion->event->eventensembles->count() === 2)
                                            <td>

                                                @if($eventversion->event->eventensembles[1]->acceptanceStatus($eventversion, $registrant) === 'MX')
                                                    -
                                                @else
                                                    {{ $eventversion->event->eventensembles[1]->acceptanceStatus($eventversion, $registrant) }}
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </div>
                        @endif
                    </section>

                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
@endsection

