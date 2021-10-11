@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem;">
                        <div>
                            <h4>
                                Event Administration
                            </h4>
                            <ul>
                                <li><a href="{{ route('eventadministrator.participatingteachers') }}">
                                        Participating Teachers
                                    </a>
                                </li>
                                <li>@if($eventversion->filecontenttypes->count() &&
                                            $eventversion->instrumentations()->count() &&
                                            $eventversion->rooms->count())
                                        <a href="{{ route('eventadministrator.adjudicators.index') }}"
                                            title="Assign members to event adjudicator positions"
                                        >
                                            Judge Assignments
                                        </a>
                                    @else
                                        Judge Assignments
                                    @endif
                                    <ul>
                                        <li>
                                            <a href="{{ route('eventadministrator.segments') }}"
                                               title="Define the major audition parts (ex. scales,solo,etc."
                                            >
                                                Audition Segments ({{ $eventversion->filecontenttypes->count() }})
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('eventadministrator.instrumentations') }}"
                                               title="Define the instrumentation to be adjudicated"
                                               >
                                                Voice Parts ({{ $eventversion->instrumentations()->count() }})
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('eventadministrator.rooms') }}"
                                               title="Define the adjudication room segments and instrumentations"
                                            >
                                                Room Definitions ({{ $eventversion->rooms()->count() }})
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4>
                                Score Definition
                            </h4>
                            <ul>
                                <li>
                                    <a href="{{ route('eventadministrator.scoring.segments') }}">
                                        Segments
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('eventadministrator.scoring.components') }}">
                                        Value Components
                                    </a>
                                </li>
                                <li>Tolerance</li>
                                <li>Score Legend</li>
                            </ul>
                        </div>

                        <div>
                            <h4>
                                Tab Room
                            </h4>
                            <ul>
                                <li>Score Input</li>
                                <li>Registrant Updates</li>
                                <li>Reports</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
