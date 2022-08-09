@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout  :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Eventversion Administration: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem; padding-bottom: 0;">
                        <h4>Event Version Configuration</h4>
                        <ul>
                            <li>
                                <a href="{{ route('eventadministration.eventversion.dates.edit') }}">
                                    Event version Dates
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('eventadministration.eventversion.members.edit') }}">
                                    Event version Members
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('eventadministration.eventversion.roles.index') }}">
                                    Event version Roles
                                </a>
                            </li>

                            <li>Score Definition
                                <ul>
                                    <li>
                                        <a href="{{ route('eventadministrator.scoring.segments',
                                                ['eventversion' => $eventversion]) }}">
                                            Segments
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('eventadministrator.scoring.components',
                                                ['eventversion' => $eventversion]) }}">
                                            Value Components
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div style="padding: 1rem .5rem;">
                        <div>
                            <h4>
                                Event Version Administration
                            </h4>
                            <ul>
                                {{-- OBLIGATION ACKNOWLEDGED TEACHERS --}}
                                <li><a href="{{ route('eventadministrator.acknowledgedteachers',
                                        ['eventversion' => $eventversion]) }}"
                                    >
                                        Obligation Acknowledged Teachers
                                    </a>
                                </li>

                                {{-- PARTICIPATING TEACHERS --}}
                                <li><a href="{{ route('eventadministrator.participatingteachers',
                                        ['eventversion' => $eventversion]) }}"
                                    >
                                        Participating Teachers
                                    </a>
                                </li>

                                {{-- STUDENT COUNTS --}}
                                <li><a href="{{ route('eventadministrator.studentcounts',
                                        ['eventversion' => $eventversion]) }}"
                                    >
                                        Student Counts
                                    </a>
                                </li>

                                {{-- STUDENT COUNTS --}}
                                <li><a href="{{ route('eventadministrator.studentcounts',
                                        ['eventversion' => $eventversion]) }}"
                                    >
                                        Student Counts
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


                        <div>
                            <h4>
                                Tab Room
                            </h4>
                            <ul>
                        <!--        <li>Score Input</li> -->
                                <li>
                                    <a href="{{ route('eventadministrator.tabroom.scoretracking',
                                        ['eventversion' => $eventversion]) }}">
                                        Score Tracking
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('eventadministrator.tabroom.scoretrackingByAdjudicator', $eventversion) }}">
                                        Score Tracking By Room + Adjudicator
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('eventadministrator.tabroom.scoretrackingByRoom', $eventversion) }}">
                                        Score Tracking By Room
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('eventadministrator.media.downloads',['eventversion' => $eventversion]) }}">
                                        View and Download media files
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('eventadministrator.tabroom.cutoffs',
                                                ['eventversion' => $eventversion]) }}">
                                        Cut-Offs
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('eventadministrator.tabroom.results',
                                                ['eventversion' => $eventversion]) }}">
                                        Detailed Audition Results
                                    </a>
                                </li>
                        <!--        <li>Registrant Updates</li> -->
                                <li>
                                    <a href="{{ route('eventadministrator.tabroom.reports',
                                                ['eventversion' => $eventversion]) }}">
                                        Reports
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('eventadministrator.tabroom.publish',
                                                ['eventversion' => $eventversion]) }}">
                                        Publish Results
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4>
                                Registration Manager
                            </h4>
                            <div>
                                <ul>
                                    <li>
                                        <a href="{{ route('registrationmanagers.index',['eventversion' => $eventversion]) }}">
                                            Participating Schools
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('registrationmanagers.registrantdetails.index',['eventversion' => $eventversion]) }}">
                                            Registrant Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('registrationmanagers.timeslotassignment.index',
                                            ['eventversion' => $eventversion]) }}">
                                            Assign Audition Timeslots
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('registrationmanagers.registrationcards.index',
                                                ['eventversion' => $eventversion]) }}" >
                                            Registration Cards
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('registrationmanagers.adjudicationforms.index',
                                                ['eventversion' => $eventversion]) }}" >
                                            Adjudication Forms
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('registrationmanagers.monitorchecklists.index',
                                                ['eventversion' => $eventversion]) }}" >
                                            Room monitor checklists
                                        </a>
                                    </li>
<!-- {{--
                                    <li>
                                        Non-Participating Schools
                                    </li>
--}} -->
                                </ul>
                            </div>
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

                        {{-- REHEARSAL MANAGER --}}
                        <div>
                            <h4>
                                Rehearsal Manager
                            </h4>

                            <ul>
                                <li>
                                    @if(config('app.url') === 'http://afdc2021.test')
                                        <a href="{{ route('rehearsalmanager.massmailings.index',
                                            [
                                                'eventversion' => $eventversion,
                                            ]) }}"
                                        >
                                            Mass Mailings
                                        </a>
                                    @else
                                        <a href="https://afdc-2021-l38q8.ondigitalocean.app/rehearsalmanager/massmailings/{{ $eventversion->id }}"
                                        >
                                            Mass Mailings
                                        </a>
                                    @endif
                                </li>
                                <li>Participant Status Change</li>
                            </ul>

                        </div>
<!-- {{--
                        <div>
                            <h4>
                                Registration Desk
                            </h4>
                            <ul>
                                <li>In-person registration</li>
                            </ul>
                        </div>
--}} -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@if(auth()->id() === 368) {{phpinfo()}} @endif
@endsection
