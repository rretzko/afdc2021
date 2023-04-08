@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Registration Manager: Registrant Details: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section id="header" style="padding: 1rem;">
                        <div class="input-group" style="display: flex; flex-direction: row; justify-content: space-around;">
                            <label for="instrumentation_id"></label>
<div style="display:flex; flex-wrap: wrap; justify-content: space-evenly; background-color: rgba(0,0,0,0.1); padding: 0.25rem;border-radius: 0.25rem; border: 1px solid darkgray;">

                            @foreach($navInstrumentations AS $key => $nav)

                                <a href="{{ route('registrationmanagers.registrantdetails.show',['eventversion' => $eventversion, 'instrumentation' => $key]) }}"                                    style="padding: 0 0.2rem; margin: 0.1rem 0; border: 1px solid darkgray; border-radius: 0.25rem; background-color: white;" >
                                    {{ strtoupper($nav[0]) }} ({{ $nav[1] }})
                                </a>
                            @endforeach
</div>
                        </div>
                    </section>

                    <section id="cards" style="padding: 0 .5rem;">
                        @if($targetinstrumentation)

                            <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                <h2>{{ strtoupper($targetinstrumentation->descr) }}</h2>

                                <div>

                                 {{--   @if(config('app.url') === 'http://afdc2021.test') --}}
                                        <a href="{{ route('registrationmanagers.registrantdetails.csv',
                                            [
                                                'eventversion' => $eventversion,
                                                'instrumentation' => $targetinstrumentation,
                                            ]) }}"
                                        >
                                            Print CSV
                                        </a>
                                    {{-- @else
                                        <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanagers/registrantdetails/csv/{{ $eventversion->id }}/{{ $targetinstrumentation->id }}">
                                            Print CSV
                                        </a>
                                    @endif --}}
                                </div>
                            </div>
                            <div style="">

                                <x-registrantdetails.registrantdetail
                                    :eventversion="$eventversion"
                                    :instrumentation="$targetinstrumentation"
                                    :registrants="$registrants"
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


