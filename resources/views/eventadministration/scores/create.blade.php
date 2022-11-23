@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">

                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div id="data-entry">

                        <h3 style="margin: 1rem;">Score Entry for {{ $eventversion->name }}</h3>

                        @livewire('tab-room.score-input-component')

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

