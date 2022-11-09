@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Tab Room: Score Tracking') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: column; justify-content: space-around">

                        {{-- LEGEND --}}
                        <div id="legend" style="display: flex; flex-direction: row; margin: auto;">
                                <div style="border:1px solid black; padding: 0 .25rem;" title="No scores found">Unauditioned</div>
                                <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(240,255,0,.3);" title="Incomplete set of scores found">Partial</div>
                                <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(0,255,0,.1);" title="Complete set of scores found">Completed</div>
                                <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(255,0,0,.1);" title="Scores are out of tolerance">Tolerance</div>
                                <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(44,130,201,.1);" title="More than expected number of scores found">Excess</div>
                                <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(0,0,0,0.2);" title="Something unexpected has occurred">Error</div>
                        </div>

                        {{-- REGISTRANT ID SECTION --}}
                        @foreach($eventversion->instrumentations() AS $instrumentation)
                            <div style="border-bottom: 1px solid lightgrey;padding-bottom: .5rem;">
                                <label style="font-weight: bold; margin-top: .5rem;">{{ strtoupper($instrumentation->descr) }} </label>
                                <div style="display: flex; flex-direction: row; flex-wrap: wrap;">
                                    @foreach($registrants AS $registrant)

                                        @if($registrant->instrumentations->first()->id === $instrumentation->id)
                                            <div style="background-color: {{ $registrant->tabroomStatusBackgroundColor() }}; border: 1px solid black; border-radius: .25rem; margin-right: .25rem; margin-bottom: .25rem; padding: 0 .1rem"
                                                title="{!! $registrant->auditionDetails() !!}">
                                                {{ $registrant->id }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection





