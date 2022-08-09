@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout  :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Eventversion Role Administration: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem; padding-bottom: 0;">

                        <h3>{{ $eventversion->name }} Roles</h3>

                        {{-- ERRORS --}}
                        <div style="display: flex; flex-direction: column;">
                            @foreach($errors->all() AS $error)
                                <div style="color: red; padding:0.25rem; margin: 0.25rem 0;">{{ $error }}</div>
                            @endforeach
                        </div>

                        {{-- SUCCESS --}}
                        @if(Session::has('status'))
                            <div style="display: flex; flex-direction: column; background-color: rgba(0,255,0,0.1); padding: 0.25rem; margin: 0.25rem 0;">
                               {!! \Session::get('status') !!}
                            </div>
                        @endif

                        <style>
                            .cell{border: 1px solid black; text-align: center; width: 5rem; padding: 0 0.25rem;}
                            .cell.narrow{min-width: 5rem; max-width: 5rem;}
                            .cell.wide{min-width: 12rem; max-width: 12rem;}
                        </style>
                        <div id="membership-rows" style="display: flex; flex-direction: column; min-width: 40rem; max-width: 40rem;">
                            {{-- HEADERS --}}
                            <div style="display: flex; flex-direction: row; font-size: 0.66rem;">
                                <div class="cell narrow">###</div>
                                <div class="cell wide" style="text-align: left;">Member</div>
                                <div class="cell chkbx">Mgr</div>
                                <div class="cell chkbx">Registration</div>
                                <div class="cell chkbx">Tab Room</div>
                                <div class="cell chkbx">Rehearsal</div>
                                <div class="cell narrow"></div>
                            </div>
                            @foreach($memberships->sortBy('user.person.last') AS $membership)
                                <form method="post" action="{{ route('eventadministration.eventversion.roles.update',['membership' => $membership]) }}" style="display: flex; flex-direction: row;">
                                    @csrf
                                    <div class="cell narrow">{{ $loop->iteration }}</div>
                                    <div class="cell wide" style="text-align: left;">{{ $membership->user->person->fullnameAlpha() }}</div>
                                    <div class="cell chkbx">
                                        <input type="checkbox" name="roles[20]" value="20"
                                            @if($membership->eventversionroles()->contains('roletype_id',20)) checked @endif
                                        />
                                    </div>
                                    <div class="cell chkbx">
                                        <input type="checkbox" name="roles[6]" value="6"
                                            @if($membership->eventversionroles()->contains('roletype_id',6)) checked @endif
                                        />
                                    </div>
                                    <div class="cell chkbx">
                                        <input type="checkbox" name="roles[21]" value="21"
                                            @if($membership->eventversionroles()->contains('roletype_id',21)) checked @endif
                                        />
                                    </div>
                                    <div class="cell chkbx">
                                        <input type="checkbox" name="roles[7]" value="7"
                                            @if($membership->eventversionroles()->contains('roletype_id',7)) checked @endif
                                        />
                                    </div>
                                    <div class="cell narrow">
                                        <input type="submit" name="submit" value="Update" style="font-size: 0.66rem; padding: 0;" />
                                    </div>
                                </form>
                            @endforeach
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@if(auth()->id() === 368) {{phpinfo()}} @endif
@endsection
