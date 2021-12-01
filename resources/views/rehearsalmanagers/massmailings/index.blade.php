@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout  :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Rehearsal Manager: Mass Mailings: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem; padding-bottom: 0;">
                        <h4>Mass Mailing Templates</h4>
                        <ul>
                            <li>Students
                                <ul>
                                    <li>
                                        Absent Student
                                    </li>
                                    <li>
                                        Early Exit
                                    </li>
                                    <li>
                                        Late Arrival
                                    </li>
                                </ul>
                            </li>
                            <li>Teachers
                                <ul>
                                    <li>
                                        <a href="{{ route('rehearsalmanager.massmailings.concert.index',
                                            [
                                                'eventversion' => $eventversion,
                                            ]) }}"
                                        >
                                            Concert
                                        </a>
                                    </li>
                                    <li>
                                        Rehearsal
                                    </li>
                                    <li>
                                        Reminder
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

