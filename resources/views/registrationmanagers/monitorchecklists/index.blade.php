@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Registration Manager: Monitor Checklists: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section id="header" style="padding: 1rem;">
                        <div class="input-group" style="display: flex; flex-direction: row; justify-content: space-around;">
                            <label for="instrumentation_id"></label>
                            <div style="display:flex; flex-wrap: wrap; justify-content: center; border: 1px solid darkgray; background-color: rgba(0,0,0,0.1); padding: 0.2rem; border-radius: 0.25rem;">
                                <!-- {{--
                                @foreach($instrumentations AS $instrumentation)
                                    <a href="{{ route('registrationmanagers.monitorchecklists.show',
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
                                --}} -->
                                @foreach($rooms AS $room)
                                    <a href="{{ route('registrationmanagers.monitorchecklists.show',
                                                [
                                                    'eventversion' => $eventversion,
                                                    'room' => $room
                                                ]
                                            )}}"
                                       style="background-color: white; border: 1px solid darkgray; margin: 0.1rem; padding: 0 0.2rem; border-radius: 0.25rem;"
                                    >
                                        {{ strtoupper($room->descr) }} ({{ $room->auditioneesCount() }})
                                    </a>
                                @endforeach
                            </div>

                        </div>
                    </section>

                    <section id="cards" style="padding: 0 .5rem;">
                        @if($targetroom && $targetroom->id)

                            <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                <h2>{{ strtoupper($targetroom->descr) }}</h2>

                                <div>
                                    {{-- @if(config('app.url') === 'http://afdc2021.test') --}}
                                        <a href="{{ route('registrationmanagers.monitorchecklists.pdf',
                                            [
                                                'eventversion' => $eventversion,
                                                'room' => $targetroom,
                                            ]) }}"
                                        >
                                            Print PDF
                                        </a>
                                    {{--
                                    @else
                                        <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanagers/monitorchecklists/pdf/{{ $eventversion->id }}/{{ $targetinstrumentation->id }}">
                                            Print PDF
                                        </a>
                                    @endif
                                    --}}
                                </div>
                            </div>
                            <div style="">

                                @foreach($rooms AS $room)

                                    <x-monitorchecklists.monitorchecklist
                                        :eventversion="$eventversion"
                                        :room="$targetroom"
                                        :registrants="$registrants"
                                    />

                                @endforeach
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


