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
                                <a href="{{ route('registrationmanagers.registrationcards.show',
                                            [
                                                'eventversion' => $eventversion,
                                                'instrumentation' => $instrumentation
                                            ]
                                        )}}"
                                >
                                    {{ strtoupper($instrumentation->descr) }} ({{ $registrationactivity->registeredInstrumentationTotalCount($instrumentation) }})
                                </a>
                            @endforeach

                            {{-- BLANK REGISTRATION CARDS --}}
                            @if($eventversion->event->id === 1)
                                <a href="{{ route('registrationmanagers.registrationcards.blanks',
                                            [
                                                'eventversion' => $eventversion,
                                            ]
                                        )}}">
                                    Blanks
                                </a>
                            @endif

                        </div>
                    </section>

                    <section id="cards" style="padding: 0 .5rem;">
                        @if($targetinstrumentation)

                            <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                <h2>{{ strtoupper($targetinstrumentation->descr) }}</h2>

                                <div>
                                    @if(config('app.url') === 'http://afdc2021.test')
                                        <a href="{{ route('registrationmanagers.registrationcards.pdf',
                                            [
                                                'eventversion' => $eventversion,
                                                'instrumentation' => $targetinstrumentation,
                                            ]) }}"
                                        >
                                            Print PDF
                                        </a>
                                    @else
                                        <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/registrationcards/pdfs/{{ $eventversion->id }}/{{ $targetinstrumentation->id }}">
                                            Print PDF
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; flex-wrap:wrap; ">
                                @foreach($registrationactivity->registrantsBySchoolNameFullnameAlpha($targetinstrumentation) AS $registrant)

                                        @if($eventversion->id === 70)
                                            @if(
                                                    ($targetinstrumentation->id === 63) ||
                                                    ($targetinstrumentation->id === 64) ||
                                                    ($targetinstrumentation->id === 65) ||
                                                    ($targetinstrumentation->id === 66)
                                                )
                                                    <x-registrationcards.1.70.double
                                                        :eventversion="$eventversion"
                                                        :instrumentation="$targetinstrumentation"
                                                        :registrant="$registrant"
                                                        :rooms="$rooms"
                                                    />
                                                @else
                                                    <x-registrationcards.1.70.single
                                                        :eventversion="$eventversion"
                                                        :instrumentation="$targetinstrumentation"
                                                        :registrant="$registrant"
                                                        :rooms="$rooms"
                                                    />
                                                @endif
                                        @endif

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


