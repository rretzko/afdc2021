@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Registration Manager: $eventversion->name Audition Timeslots") }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="margin:auto;">
                        <x-tables.timeslots
                            :eventversion="$eventversion"
                            csrf="{{ csrf_token() }}"
                            route="{{ (config('app.url') === 'http://afdc2021.test') ? route('registrationmanagers.timeslotassignment.update') : 'https://afdc-2021-l38q8.ondigitalocean.net/registrationmanager/timeslots/update' }}"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


