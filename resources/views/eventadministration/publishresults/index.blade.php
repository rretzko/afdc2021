@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Publish Results: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section style="margin: auto; padding: 1rem 0; ">

                        <div style="font-size: 2rem; margin-bottom: 1rem;">
                            <a href="{{ route('eventadministrator.tabroom.publish.update',['eventversion' => $eventversion]) }}">
                                <style>
                                    button:hover{background-color: lightgrey;}
                                </style>
                                <button style="border-radius: 1rem; ">
                                    @if($currentdt) Remove Results @else Publish Results @endif
                                </button>
                            </a>
                        </div>

                        <div >
                            @if($currentdt)
                                Last Published: {{ $currentdt }}
                            @endif
                        </div>
                    </section>

                </div>
            </div>

        </div>
    </div>

@endsection

