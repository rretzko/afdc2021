@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Registration Manager: Adjudication Forms: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section id="header" style="padding: 1rem;">
                        <div class="input-group" style="display: flex; flex-direction: row; justify-content: space-around;">
                            <label for="instrumentation_id"></label>
                            <div style="display:flex; flex-wrap: wrap; justify-content: center; border: 1px solid darkgray; background-color: rgba(0,0,0,0.1); padding: 0.2rem; border-radius: 0.25rem;">

                                @if(isset($instrumentations)) {{-- DEFAULT SETTING --}}
                                    @foreach($instrumentations AS $instrumentation)
                                        <a href="{{ route('registrationmanagers.adjudicationforms.show',
                                                    [
                                                        'eventversion' => $eventversion,
                                                        'instrumentation' => $instrumentation
                                                    ]
                                                )}}"
                                           style="background-color: white; border: 1px solid darkgray; margin: 0.1rem; padding: 0 0.2rem; border-radius: 0.25rem;"
                                        >
                                            {{ strtoupper($instrumentation->descr) }} ({{ $registrationactivity->registeredInstrumentationTotalCount($instrumentation) }})
                                        </a>
                                    @endforeach
                                @endif

                                {{-- FOR NJ ALL-SHORE --}}
                                @if(isset($rooms))
                                        @foreach($rooms AS $targetroom)
                                            <a href="{{ route('registrationmanagers.adjudicationformsbyroom.show',
                                                    [
                                                        'eventversion' => $eventversion,
                                                        'room' => $targetroom
                                                    ]
                                                )}}"
                                               style="background-color: white; border: 1px solid darkgray; margin: 0.1rem; padding: 0 0.2rem; border-radius: 0.25rem;"
                                            >
                                                {{ $targetroom->descr }}
                                            </a>
                                        @endforeach
                                @endif

                            </div>
                        </div>
                    </section>

                    <section id="cards" style="padding: 0 .5rem;">
                        @if(isset($targetinstrumentation) && $targetinstrumentation) {{-- DEFAULT SETTING --}}

                            <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                <h2>{{ strtoupper($targetinstrumentation->descr) }}</h2>

                                <div>
                                   <!-- {{-- @if(config('app.url') === 'http://afdc2021.test') --}} -->
                                        <a href="{{ route('registrationmanagers.adjudicationforms.pdf',
                                            [
                                                'eventversion' => $eventversion,
                                                'instrumentation' => $targetinstrumentation,
                                            ]) }}"
                                        >
                                            Print PDF
                                        </a>
                                    <!-- {{-- @else
                                        <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanagers/adjudicationforms/pdf/{{ $eventversion->id }}/{{ $targetinstrumentation->id }}">
                                            Print PDF
                                        </a>
                                    @endif --}} -->
                                </div>
                            </div>
                            <div style="">

                                @foreach($rooms AS $room)

                                    <x-adjudicationforms.25.73.adjudicationform
                                        :eventversion="$eventversion"
                                        :instrumentation="$targetinstrumentation"
                                        :registrants="$registrants"
                                        :room="$room"
                                    />

                                @endforeach
                            </div>

                        @endif

                        {{-- NJ ALL-SHORE --}}
                        @if(isset($room) && $room)
                                <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                    <h2>{{ strtoupper($room->descr) }}</h2>

                                    <div>

                                        <a href="{{ route('registrationmanagers.adjudicationformsbyroom.pdf',
                                            [
                                                'eventversion' => $eventversion,
                                                'room' => $room,
                                            ]) }}"
                                        >
                                            Print PDF
                                        </a>

                                    </div>
                                </div>
                                <div style="">

                                        <x-adjudicationforms.19.72.adjudicationform
                                            :eventversion="$eventversion"
                                            :registrants="$registrants"
                                            :room="$room"
                                            type="{{ strpos($room->descr, 'olo') ? 'solo' : 'scales' }}"
                                        />

                                </div>
                        @endif

                    </section>

                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
@endsection


