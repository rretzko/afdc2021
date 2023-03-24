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
                        <h4>Dashboard</h4>
                        <ul>
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- EVENT CONFIGURATION --}}
                    <div style="padding: 1rem .5rem; padding-bottom: 0;">
                        <h4>Event Version Configuration</h4>
                        <ul>
                            <li>
                                <a href="{{ route('eventadministration.eventversion.eventconfigurations.edit') }}">
                                    Event Configuration
                                </a>
                            </li>

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

                    {{-- EVENT ADMINISTRATION --}}
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

                                {{-- Event Milestone Tracking --}}
                                <li>
                                    <a href="{{ route('eventadministration.milestones', ['eventversion' => $eventversion]) }}">
                                        Event Milestone Tracking (future)
                                    </a>
                                </li>

                            </ul>

                        </div>


                        <div>
                            <h4>
                                Tab Room
                            </h4>
                            <ul>
                                <li>
                                    <a href="{{ route('eventadministrator.scores.create') }}">
                                        Score Input
                                    </a>
                                </li>
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

                        {{-- REGISTRATION MANAGER --}}
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
                                        </a> <span style="font-size: small;"> (includes info dump csv by voice part)</span>
                                    </li>
                                    <li>
                                        @if($eventversion->eventversionconfig->virtualaudition)
                                            <span style="color: lightgray;">No Timeslots Required (virtual audition)</span>
                                        @else
                                            <a href="{{ route('registrationmanagers.timeslotassignment.index',
                                                ['eventversion' => $eventversion]) }}">
                                                Assign Audition Timeslots
                                            </a>
                                        @endif
                                    </li>
                                    <li>
                                        @if($eventversion->eventversionconfig->virtualaudition)
                                            <span style="color: lightgray;">No Registration Cards (virtual audition)</span>
                                        @else
                                            <a href="{{ route('registrationmanagers.registrationcards.index',
                                                    ['eventversion' => $eventversion]) }}" >
                                                Registration Cards
                                            </a>
                                        @endif
                                    </li>
                                    <li>
                                        <a href="{{ route('registrationmanagers.adjudicationformsbyroom.index',
                                                ['eventversion' => $eventversion]) }}" >
                                            Adjudication Forms By Room
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('registrationmanagers.monitorchecklists.index',
                                                ['eventversion' => $eventversion]) }}" >
                                            Room monitor checklists
                                        </a>
                                    </li>
                                    {{-- REPORTS --}}
                                    <li>
                                        Reports
                                        <ul>
                                            <li>
                                                <a href="{{ route('registrationmanagers.registrantdetails.all.csv',['eventversion' => $eventversion]) }}">
                                                    All Registrants info dump csv by school by name
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('registrationmanagers.registrationdetails.all.csv',['eventversion' => $eventversion]) }}">
                                                    All Registrants roster csv by timeslot by school by name
                                                </a>
                                            </li>
                                        </ul>
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
                        <style>
                            li{}
                            .link-def{display: flex; flex-direction: row; border-top: 1px solid black; padding: 0.25rem 0;}
                            .def{margin-left: 1rem; font-style: italic; width: 75%;}
                            .link{width: 25%;}
                        </style>
                        <div>
                            <h4>
                                Rehearsal Manager
                            </h4>

                            <ul style="list-style-type: none;">
                                @if($eventversion->eventversionconfig->participation_fee)
                                    <li>
                                        <div class="link-def"  style="border-top: 0;">
                                            <a href="{{ route('rehearsalmanager.participationfee.index') }}"
                                                class="link"
                                            >
                                                Participation Fee Roster
                                            </a>
                                            <div class="def">
                                                Roster of participating schools (alpha) with count of students, PayPal
                                                cumulative payments, cumulative Teacher payments, and balance due.
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="link-def">
                                            <a href="{{ route('rehearsalmanager.paypalreconciliation.index') }}"
                                                class="link"
                                            >
                                                PayPal Reconciliation Roster
                                            </a>
                                            <div class="def">
                                                Roster of PayPal payments by date paid with payee name, registrant id
                                                (if student) school name, amount and date.
                                            </div>
                                        </div>
                                    </li>

                                    {{-- REMOVED STUDENTS ROSTER --}}
                                    <li>
                                        <div class="link-def">
                                            <a class="link" href="{{ route('rehearsalmanager.removedstudentroster.index') }}">
                                                    'Removed Student' Roster
                                            </a>
                                            <div class="def">
                                                Change student participation status to/from 'Removed'.<br />
                                                'Removed' status prohibits student from participating in next event.
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                <!-- {{--
                                <li>
                                    <a href="{{ route('rehearsalmanager.massmailings.index',
                                        [
                                            'eventversion' => $eventversion,
                                        ]) }}"
                                    >
                                        Mass Mailings
                                    </a>
                                </li>
                                <li>Participant Status Change</li>
                                --}} -->
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
