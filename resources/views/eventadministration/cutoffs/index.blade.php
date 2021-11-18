@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Cut-Offs: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                        <section id="summary" style="padding: .5rem; margin: auto;">
                            <style>
                                td,th{border: 1px solid black; padding:0 .25rem;}
                            </style>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="border-top: 0; border-left: 0;"></th>
                                        @foreach($eventversion->instrumentations() AS $instrumentation)
                                            <th>
                                                {{ strtoupper($instrumentation->abbr) }}
                                            </th>
                                        @endforeach
                                        <th>Total</th>
                                        <th style="border-top: 0; border-right: 0;"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($eventensembles AS $eventensemble)
                                    <tr style="background-color: {{ $eventensemblecutoff->eventensembleLegendColor($eventensemble) }};">
                                        <th>{{ $eventensemble->name }}</th>
                                        @foreach($eventversion->instrumentations() AS $instrumentation)
                                            <td>
                                                {{ $eventensemblecutoff->countByCutoffEventensembleInstrumentation($eventensemble, $instrumentation) }}
                                            </td>
                                        @endforeach
                                        <td style="text-align: center;">
                                            {{ $eventensemblecutoff->countAccepted($eventensemble) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('eventadministrator.lock',
                                                [
                                                    'eventversion' => $eventversion,
                                                    'eventensemble' => $eventensemble,
                                                ]) }}">
                                                @if($eventensemblelocks->where('eventensemble_id', $eventensemble->id)->first())
                                                    @if($eventensemblelocks->where('eventensemble_id', $eventensemble->id)->first()->locked)
                                                        UnLock
                                                    @else
                                                        Lock
                                                    @endif
                                                @else
                                                    Lock
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </section>

                        <section id=detail" style="display: flex; flex-direction: column; border-top: 1px solid darkgrey; padding-top: .5rem;">
                            <div style="display: flex; flex-direction: row; justify-content: space-around;">
                                @foreach($eventversion->instrumentations() AS $instrumentation)
                                    <div style="display: flex; flex-direction: column;">
                                        <label style="font-weight: bold; text-decoration: underline;">
                                            {{ strtoupper($instrumentation->abbr) }}
                                        </label>
                                        <div class="data">
                                            @foreach($adjudication->grandtotalByInstrumentation($instrumentation) AS $scoresummary)

                                                <div style="padding: 0 .25rem; background-color: {{ $eventensemblecutoff->backgroundColorByScoreInstrumentation($scoresummary, $instrumentation) }}">

                                                    <a href="{{ route('eventadministrator.tabroom.cutoffs.update',
                                                        [
                                                            'eventversion_id' => $eventversion->id,
                                                            'instrumentation_id' => $instrumentation->id,
                                                            'cutoff' => $scoresummary->score_total,
                                                        ]) }}
                                                    ">
                                                        {{ $scoresummary->score_total }}
                                                    </a>

                                                </div>

                                            @endforeach
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        </section>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

