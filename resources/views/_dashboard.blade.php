@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Dashboard') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="container" id="objects_Wrapper">
                            <div class="instructions mb-3 text-center">
                                Click the buttons to update your event settings...
                            </div>
                            <style>
                                a{color: darkblue;}
                                a:hover{color: darkred;}
                                li{color: darkgrey;}
                                table{background-color: white;border-collapse: collapse;margin: auto;}
                                td,th{border: 1px solid darkblue; padding:0 4px;}
                                th{text-align: center;}
                                .btn{font-size: .8rem;padding:0 4px;margin-bottom: 2px;}
                            </style>

                            <table>
                                <tr>
                                    <th>Topic</th>
                                    <th>Current</th>
                                    <th></th>
                                </tr>

                                <tr>
                                    <td>Organization</td>
                                    <th class="text-left">{{ $dashboard->organization() }} </th>
                                    <td class="text-center"><a href="{{ route('organizations') }}"
                                                               title="Add/edit your organization(s)"
                                                               class="btn btn-info"
                                        >
                                            @if($dashboard->organization() === 'None found') Add @else Change @endif
                                        </a>
                                    </td>
                                </tr>

                                @if($dashboard->organization() !== 'None found')
                                    <tr>
                                        <td>Members</td>
                                        <th class="text-left">{{ $dashboard->members() }}</th>
                                        <td class="text-center">
                                            <a href="{{ route('members') }}"
                                               title="Organization members"
                                               class="btn btn-info"
                                            >
                                                @if($dashboard->members() === 'None found') Add @else Change @endif
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Event</td>
                                        <th class="text-left">{{ $dashboard->event() }}</th>
                                        <td class="text-center"><a href="{{ route('events') }}"
                                                                   title="Add/edit your event(s)"
                                                                   class="btn btn-info"
                                            >
                                                @if((! $dashboard->event()) || ($dashboard->event() === 'None found'))
                                                    Add
                                                @else
                                                    Change
                                                @endif
                                            </a>
                                        </td>
                                    </tr>

                                    @if($dashboard->event())
                                        <tr>
                                            <td>Eventversion</td>
                                            <th class="text-left">{{ $dashboard->eventversion() }}</th>
                                            <td class="text-center"><a href="{{ route('eventversions') }}"
                                                                       title="Add/edit your event(s)"
                                                                       class="btn btn-info"
                                                >
                                                    @if((! $dashboard->eventversion()) || ($dashboard->eventversion() === 'None found'))
                                                        Add
                                                    @else
                                                        Change
                                                    @endif
                                                </a>
                                            </td>
                                        </tr>
                                        @if($dashboard->eventversion())
                                            <tr>
                                                <td>Statistics for:</td>
                                                <th class="text-left">{{ $dashboard->eventversion() }}</th>
                                                <td class="text-center"><a href="{{ route('evstats') }}"
                                                                           title="Review Event statistics"
                                                                           class="btn btn-info"
                                                    >
                                                        Stats
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Invitations for:</td>
                                                <th class="text-left">{{ $dashboard->invitations() }}</th>
                                                <td class="text-center"><a href="{{ route('evinvitations') }}"
                                                                           title="Add/Remove event invitations"
                                                                           class="btn btn-info"
                                                    >
                                                        Invitations
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Ensembles for:</td>

                                                <th class="text-left">{!! $dashboard->ensembles() !!}</th>
                                                <td class="text-center"><a href="{{ route('evstats') }}"
                                                                           title="Event ensembles"
                                                                           class="btn btn-info"
                                                    >
                                                        @if($dashboard->ensembles() === 'None found') Add @else Change @endif
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Rules</td>
                                                <th class="text-left">{!! $dashboard->rules() !!}</th>
                                                <td class="text-center"><a href="{{ route('rules') }}"
                                                                           title="Event rules"
                                                                           class="btn btn-info"
                                                    >
                                                        @if($dashboard->rules() === 'None found') Add @else Change @endif
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif

                                    @endif
                                    @if($r_mgr_event !== 'None found')
                                        <tr>
                                            <td>Rehearsal Mgr</td>
                                            <td class="text-left"><b>{{ $r_mgr_event }}</b></td>
                                            <td class="text-center">
                                                <a href="{{ route('videos.download') }}"
                                                   title="Event videos"
                                                   class="btn btn-info"
                                                >
                                                    Videos
                                                </a>
                                            </td>
                                        </tr>
                                    @endif

                                @endif

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

