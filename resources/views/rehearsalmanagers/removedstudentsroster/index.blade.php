@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Removed Student: $eventversion->name ") }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: column; justify-content: space-around">

                        {{-- DEFINITION --}}
                        <div id="definition">
                            <div style="font-style: italic; text-align: center; margin-bottom: 1rem;">
                                def. Roster of students who have been removed from the current event and thereby
                                prohibited from auditioning for the next event.
                            </div>
                        </div>

                        {{-- FORM --}}
                        <div style="display: flex; flex-direction: column; border: 1px solid black; background-color: rgba(0,0,0,0.1); padding: 0.25rem; margin-bottom: 1rem; margin: auto;">
                            @if($student)
                                Edit Form goes here...
                            @else
                                <form method="post" action="{{ route('rehearsalmanager.removedstudentroster.store') }}">

                                    @csrf

                                    <h3>
                                        Accepted Participants ({{ count($acceptedParticipants) }})
                                    </h3>

                                    <div class="input-group" style="display: flex; flex-direction: column; margin-bottom: 0.5rem;">
                                        <label for="registrant_id">
                                            Select Participant to Remove
                                        </label>
                                        <select name="registrant_id" autofocus>
                                            <option value="0">Select Participant</option>
                                            @foreach($acceptedParticipants AS $participant)
                                                <option value="{{ $participant->id }}">
                                                    {{ $participant->nameAlpha }} ({{ $participant->schoolName }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="input-group" style="">
                                        <input type="submit" name="submit" value="Submit" />
                                    </div>
                                </form>

                            @endif
                        </div>

                        {{-- PROHIBITED STUDENT TABLE --}}
                        <div>
                            <style>
                                table{border-collapse: collapse;margin: auto; margin-top: 1rem;}
                                td,th{border: 1px solid black; padding: 0 0.25rem;}
                            </style>
                            <table>
                                <thead>
                                <tr>
                                    <td colspan="3" style="border: 1px solid white; border-bottom: 1px solid black;"></td>
                                    <td style="border: 1px solid white; border-bottom: 1px solid black; font-size: small; text-align: right;">
                                        <a href="{{ route("rehearsalmanager.participationfee.export") }}">
                                            Download csv
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">###</th>
                                    <th style="text-align: left;">Student</th>
                                    <th style="text-align: left;">School</th>
                                    <th style="text-align: center;">Voice</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @forelse($removeds AS $removed)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $removed->fullNameAlpha }}</td>
                                            <td>{{ $removed->schoolName }}</td>
                                            <td style="text-align: center;">{{ $removed->instrumentations->first()->formattedDescr() }}</td>
                                        </tr>
                                    @empty
                                        <td colspan="3" style="text-align: center;">No students found</td>
                                    @endforelse
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




