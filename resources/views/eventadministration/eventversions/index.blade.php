@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$event" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section style="margin: auto; padding: 1rem 0; ">

                        {{-- EVENTVERSIONS --}}
                        <div>
                            @if($eventversionroles->count())
                                <h2>Event Administration : {{ $event->name }}</h2>

                                {{-- Add New Event --}}
                                <div>
                                    <a href="{{ route('eventadministration.eventversion.create') }}">
                                        Add new event
                                    </a>
                                </div>

                                <div>
                                    @foreach($eventversions AS $eventversion)
                                        <div style="font-size: 1.15rem;">
                                            <a href="{{route("eventadministration.eventversion.index",
                                                [
                                                  'eventversion' => $eventversion->id,
                                                ])}}">
                                                    {{$eventversion->name}}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </section>

                </div>
            </div>

        </div>
    </div>

@endsection


