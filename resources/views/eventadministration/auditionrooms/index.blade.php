@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Audition Rooms') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: row; justify-content: space-around">

                        {{-- ROOM FORM --}}
                        <x-eventadministration.rooms.roomform
                            :room="$room"
                            :filecontenttypes="$filecontenttypes"
                            :instrumentations="$instrumentations"
                        />

                        {{-- ROOM TABLE --}}
                        <x-eventadministration.rooms.roomtable
                            :rooms="$rooms"
                        />

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


