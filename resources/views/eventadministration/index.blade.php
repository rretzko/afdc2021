@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Eventversion Administration: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem;">
                        <div>
                            <h4>
                                Eventversion Administration
                            </h4>
                            <ul>
                                {{-- PARTICIPATING TEACHERS --}}
                                <li><a href="{{ route('eventadministrator.participatingteachers',
                                        ['eventversion' => $eventversion]) }}"
                                    >
                                        Participating Teachers***
                                    </a>
                                </li>

                                <li>@if($eventversion->filecontenttypes->count() &&
                                            $eventversion->instrumentations()->count() &&
                                            $eventversion->rooms->count())
                                        <a href="{{ route('eventadministrator.adjudicators.index',
                                                ['eventversion' => $eventversion]) }}"
                                            title="Assign members to event adjudicator positions"
                                        >
                                            Judge Assignments
                                        </a>
                                    @else
                                        Judge Assignments
                                    @endif
                                    <ul>
                                        <li>
                                            <a href="{{ route('eventadministrator.segments',
                                                ['eventversion' => $eventversion]) }}"
                                               title="Define the major audition parts (ex. scales,solo,etc."
                                            >
                                                Audition Segments ({{ $eventversion->filecontenttypes->count() }})
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('eventadministrator.instrumentations',
                                                ['eventversion' => $eventversion]) }}"
                                               title="Define the instrumentation to be adjudicated"
                                               >
                                                Voice Parts ({{ $eventversion->instrumentations()->count() }})
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('eventadministrator.rooms',
                                                ['eventversion' => $eventversion]) }}"
                                               title="Define the adjudication room segments and instrumentations"
                                            >
                                                Room Definitions ({{ $eventversion->rooms()->count() }})
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        {{-- REGISTRATION DESK --}}
                        <div>
                            <h4>
                                Registration Desk
                            </h4>
                            <ul>
                                <li>In-person registration</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
