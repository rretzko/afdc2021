@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Registration Manager: Registrant Details: Change Voice Part: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section id="header" style="padding: 1rem;">
                        <div class="input-group" style="display: flex; flex-direction: row; justify-content: space-around;">
                            <label for="instrumentation_id"></label>
<div style="display:flex; flex-wrap: wrap; justify-content: space-evenly; background-color: rgba(0,0,0,0.1); padding: 0.25rem;border-radius: 0.25rem; border: 1px solid darkgray;">
                            @foreach($instrumentations AS $instrumentation)
                                <a href="{{ route('registrationmanagers.registrantdetails.show',
                                            [
                                                'eventversion' => $eventversion,
                                                'instrumentation' => $instrumentation
                                            ]
                                        )}}"
                                   style="padding: 0 0.2rem; margin: 0.1rem 0; border: 1px solid darkgray; border-radius: 0.25rem; background-color: white;"
                                >
                                    {{ strtoupper($instrumentation->descr) }} ({{ $registrationactivity->registeredInstrumentationTotalCount($instrumentation) }})
                                </a>
                            @endforeach
</div>
                        </div>
                    </section>

                    <section id="cards" style="padding: 0 .5rem;">
                        <div style="margin-bottom: 1rem;">
                            <form method="post" action="{{ route('registrationmanagers.registrantdetails.updateVoicePart', ['registrant' => $registrant]) }}" style="width: 50%; margin: auto; border: 1px solid black; padding: 0.5rem;">
                                <style>
                                    label{width: 6rem;}
                                    .data{font-weight: bold;}
                                </style>

                                @csrf

                                <div class="input-group">
                                    <label>Name</label>
                                    <div class="data">{{ $registrant->fullnameAlpha }}</div>
                                </div>

                                <div class="input-group">
                                    <label>Voice Part</label>
                                    <div class="data">
                                        <select name="instrumentation_id">
                                            @foreach($instrumentations AS $instrumentation)
                                                <option value="{{ $instrumentation->id }}"
                                                    @if($registrant->instrumentations->first()->id == $instrumentation->id) selected @endif
                                                >
                                                    {{ $instrumentation->formattedDescr() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="input-group">
                                    <label></label>
                                    <div class="data">
                                        <input type="submit" name="submit" value="Update" />
                                    </div>
                                </div>
                            </form>
                        </div>

                    </section>

                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
@endsection


