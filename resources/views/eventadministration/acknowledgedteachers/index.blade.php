@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Event Administration: $eventversion->name  Acknowledged Teachers") }}
                            ({{ $teachers->count() }})
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: column; justify-content: space-around">

                        {{-- DEFINITION --}}
                        <div id="definition">
                            <div style="font-style: italic; text-align: center; margin-bottom: 1rem;">
                                def. An teacher is deemed 'acknowledged' if the teacher has acknowledged the Obligations page.
                            </div>
                        </div>

                        {{-- ADJUDICATOR TABLE --}}
                        <x-eventadministration.acknowledgedteachers.acknowledgedteacherstable
                            :eventversion="$eventversion"
                            :teachers="$teachers"
                        />

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




