@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Registration Manager: Registration Cards: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section id="header" style="padding: 1rem;">
                        <div class="input-group" style="display: flex; flex-direction: row; justify-content: space-around;">
                            <label for="instrumentation_id"></label>

                            @foreach($instrumentations AS $instrumentation)
                                <a href="{{ route('eventadministrator.tabroom.results.show',
                                            [
                                                'eventversion' => $eventversion,
                                                'instrumentation' => $instrumentation
                                            ]
                                        )}}"
                                >
                                    {{ strtoupper($instrumentation->descr) }} ({{ $registrationactivity->registeredInstrumentationTotalCount($instrumentation) }})
                                </a>
                            @endforeach

                        </div>
                    </section>

                    <section id="cards" style="padding: 0 .5rem;">
                        @foreach($instrumentations as $instrumentation)
                            <h2>{{ strtoupper($instrumentation->descr) }}</h2>
                            <div style="display: flex; flex-wrap:wrap;">
                                @foreach($registrationactivity->registeredInstrumentationTotal($instrumentation) AS $registrant)

                                        @if($eventversion->id === 70)
                                            <x-registrationcards.1.70.registrationcard
                                                :eventversion="$eventversion"
                                                :instrumentation="$instrumentation"
                                                :registrant="$registrant"
                                            />
                                        @endif

                                @endforeach
                            </div>
                        @endforeach
                    </section>

                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
@endsection


