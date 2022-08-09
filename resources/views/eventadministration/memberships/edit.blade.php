@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout  :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Eventversion Membership Administration: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem; padding-bottom: 0;">

                        <h3>{{ $eventversion->name }} Membership</h3>

                        {{-- ERRORS --}}
                        <div style="display: flex; flex-direction: column;">
                            @foreach($errors->all() AS $error)
                                <div style="color: red;">{{ $error }}</div>
                            @endforeach
                        </div>

                        {{-- SUCCESS --}}
                        @if(Session::has('status'))
                            <div style="display: flex; flex-direction: column; background-color: rgba(0,255,0,0.1); padding: 0.25rem;">
                               {!! \Session::get('status') !!}
                            </div>
                        @endif

                        <form method="post" action="{{ route('eventadministration.eventversion.membership.update',['membership' => $membership] ) }}" >

                            @csrf

                            <style>
                                .input-info-block{display: flex; flex-direction: row; background-color: rgba(0,0,0,0.1); padding:0 0.5rem;}
                                .input-info-block label{margin-right: 1rem; min-width: 6rem;}
                                .input-info-block .data{font-weight: bold;}
                                .datetype-label{font-weight: bold;}
                                .date-block{display:flex; flex-direction: column;}
                                .dateblock-label{font-size: smaller; min-width: 3rem;}
                                .hint{font-size: xx-small;}
                                .input-block{display: flex; flex-direction: column; margin-bottom: 1rem;}
                                .input-block input{max-width: 12rem;}
                            </style>

                            {{-- IDENTIFICATION --}}
                            <div class="input-info-block">
                                <label for="">Sys.Id.</label>
                                <div class="data">{{ $membership->id }}</div>
                            </div>

                            <div class="input-info-block">
                                <label for="">Organization</label>
                                <div class="data">{{ $organization->name }}</div>
                            </div>

                            <div class="input-info-block" style="margin-bottom: 1rem;">
                                <label for="">Member</label>
                                <div class="data">{{ $person->fullnameAlpha() }}</div>
                            </div>

                            {{-- MEMBERSHIP TYPE --}}
                            <div class="input-block">
                                <label for="membershiptype_id" class="datetype-label">Membership Type</label>
                                <select name="membershiptype_id" style="max-width: 12rem;">
                                    @foreach($membershiptypes AS $membershiptype)
                                        <option value="{{ $membershiptype->id }}"
                                            @if($membershiptype->id == $membership->membershiptype_id) SELECTED @endif
                                        >
                                            {{ $membershiptype->descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- MEMBERSHIP ID --}}
                            <div class="input-block">
                                <label for="membership_id" class="datetype-label">Membership ID</label>
                                <input type="text" name="membership_id" value="{{ $membership->membership_id }}" />
                            </div>

                            {{-- MEMBERSHIP ID --}}
                            <div class="input-block">
                                <label for="expiration" class="datetype-label">Expiration</label>
                                <input type="date" name="expiration" value="{{ $membership->expiration }}" />
                            </div>

                            {{-- GRADE LEVELS --}}
                            <div class="input-block">
                                <label for="grade_levels" class="datetype-label">Grade Levels</label>
                                <input type="text" name="grade_levels" value="{{ $membership->grade_levels }}" placeholder="Elementary, Middle, High School"/>
                            </div>

                            {{-- SUBJECTS --}}
                            <div class="input-block">
                                <label for="subjects" class="datetype-label">Subjects</label>
                                <input type="text" name="subjects" value="{{ $membership->subjects }}" placeholder="Chorus, theory, etc."/>
                            </div>

                            {{-- SUBMIT --}}
                            <div style="display: flex; flex-direction: column; max-width: 8rem; margin-left: 3.25rem; margin-bottom: 1rem;">
                                <label></label>
                                <input type="submit" name="submit" value="Update" />
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
