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


                        </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Topic</th>
                                <th>Current</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Organization</td>
                                <th class="text-left">{{ $dashboard->organization()->name }} </th>
                                <td class="text-center"><a href="{{ route('organizations') }}"
                                                           title="Add/edit your organization(s)"
                                                           class="btn btn-info"
                                    >
                                        Add
                                    </a>
                                </td>
                            </tr>

                            @if($dashboard->organization() !== 'None found')
                                <tr>
                                    <td>Members</td>
                                    <th class="text-left">{{ $dashboard->countmemberships() }}</th>
                                    <td class="text-center">
                                        <a href="{{ route('members') }}"
                                           title="Organization members"
                                           class="btn btn-info"
                                        >
                                            @if($dashboard->countmemberships() === 'None found') Add @else Change @endif
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Event</td>
                                    <th class="text-left">{{ $dashboard->event()->name }}</th>
                                    <td class="text-center"><a href="{{ route('dashboard.events.index') }}"
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

                                @if($dashboard->event()->id)
                                    <tr>
                                        <td>Eventversion</td>
                                        <th class="text-left">{{ $dashboard->eventversion()->name }}</th>
                                        <td class="text-center"><a href="{{ route('dashboard.eventversions.index') }}"
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

                                    @if($dashboard->eventversion()->id)
                                        <tr>
                                            <td>Invitations for:</td>
                                            <th class="text-left">{{ $dashboard->countinvitations() }}</th>
                                            <td class="text-center"><a href="{{ route('dashboard.invitations.index') }}"
                                                                       title="Add/Remove event invitations"
                                                                       class="btn btn-info"
                                                >
                                                    Invitations
                                                </a>
                                            </td>
                                        </tr>
<!-- {{--
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
--}} -->
                                    @endif {{-- eventversion --}}

                                @endif {{-- event --}}

                            @endif {{-- organization --}}

                        </tbody>
                    </table>



                </div>
            </div>
        </div>
    </div>
@endsection
