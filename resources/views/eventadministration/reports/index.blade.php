@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Reports for ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem;">
                        <div>
                            <h4>
                                Reports
                            </h4>
                            <ul>
                                @foreach($eventversion->instrumentations() AS $instrumentation)
                                    <li>
                                        <a href="{{ route('eventadministrator.tabroom.reports.auditionresults',
                                            [
                                                'eventversion' => $eventversion,
                                                'instrumentation' => $instrumentation,
                                            ]) }}"
                                        >
                                        {{ $instrumentation->formattedDescr() }} Audition Results pdf
                                        </a>
                                    </li>
                                @endforeach

                                @if(auth()->id() === 368)
                                        <li>
                                            <a href="{{ route('eventadministrator.tabroom.reports.auditionresults.all',
                                            [
                                                'eventversion' => $eventversion,
                                            ]) }}"
                                            >
                                                Print ALL Audition Results pdf (Domain Owner)
                                            </a>
                                        </li>
                                @endif


                                <li><a href="{{ route('eventadministrator.tabroom.reports.participatingdirectors',
                                        [
                                            'eventversion' => $eventversion,
                                        ]) }}"
                                    >
                                        Participating Directors
                                    </a>
                                </li>

                                <li><a href="{{ route('eventadministrator.tabroom.reports.participatingstudents',
                                    [
                                        'eventversion' => $eventversion,
                                    ]) }}"
                                    >
                                        Participating Students
                                    </a>
                                </li>
                                <!-- {{--
                                <li><a href="{{ route('eventadministrator.tabroom.reports.participatingstudents') }}">
                                        Participating Students
                                    </a>
                                </li>
                                --}} -->
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
