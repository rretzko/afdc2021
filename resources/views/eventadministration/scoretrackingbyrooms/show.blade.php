@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Tab Room: Score Tracking: '.$room->descr) }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: column; justify-content: space-around">


                        {{-- ROOMS --}}
                        <div style="display: flex; flex-direction: row; flex-wrap: wrap; ">
                            @forelse($rooms AS $item) {{-- room --}}
                                    <a href="{{ route('eventadministrator.tabroom.scoretrackingByRoom.show', $item) }}">
                                        <button style="width: 10rem; margin-right: 10px; margin-top: 10px; @if($item->id === $room->id) background-color: black; color: white; @endif">
                                            {{ $item->descr }}
                                        </button>
                                    </a>
                            @empty No Rooms Found
                            @endforelse
                        </div>

                        <div style="display: flex; flex-direction: row; flex-wrap: wrap;  justify-content: center; margin-top: 1rem; border: 1px solid black; width: 100%; padding: 0.25rem 0; background-color: rgba(0,0,0,0.1);">
                            <header style="font-weight: bold; margin-right: 1rem;">Adjudicators: </header>
                            @forelse($room->adjudicators->sortBy('adjudicatorname') AS $adjudicator)
                                <div style="border: 1px solid black; margin-right: 1rem; padding: 0 0.5rem; background-color: white;">
                                    {{ $adjudicator->adjudicatorname }}
                                </div>
                            @empty No Adjudicators Found
                            @endforelse
                        </div>

                        {{-- LEGEND --}}
                        <div id="legend" style="display: flex; flex-direction: row; margin: auto; margin-top: 1rem;">
                            <div style="border:1px solid black; padding: 0 .25rem; background-color: aliceblue;" title="No scores found">Unauditioned</div>
                            <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(240,255,0,.3);" title="Incomplete set of scores found">Partial</div>
                            <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(0,255,0,.1);" title="Complete set of scores found">Completed</div>
                            <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(255,0,0,.1);" title="Scores are out of tolerance">Tolerance</div>
                            <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(44,130,201,.2);" title="More than expected number of scores found">Excess</div>
                            <div style="border:1px solid black; padding: 0 .25rem; background-color: rgba(255,255,255,.1);" title="Something unexpected has occurred">Error</div>
                        </div>


                        <div style="margin-top: 1rem; border: 1px solid black; display: flex; flex-direction: row; flex-wrap: wrap; padding: 0.25rem;background-color: white;">
                            <header style="width: 100%; text-align: center; font-weight: bold;">{{ $room->auditionees()->count() }} Auditionees</header>
                            @forelse($room->auditionees() AS $auditionee)
                                <div style="border: 1px solid black; margin-right: 0.25rem; margin-top: 0.25rem; padding: 0 0.25rem; background-color: {{ $auditionee->roomStatusColor($room) }}">
                                    <span title="{{ $auditionee->fullnameAlpha }}&#13;@ {{ $auditionee->schoolShortname }}&#13;w/{{ $auditionee->currentTeacher->person->fullnameAlpha() }}&#13;{!! $auditionee->adjudicatorProgress($room) !!}">
                                        {{ $auditionee->id }}
                                    </span>
                                </div>
                                @empty No registrants found
                            @endforelse
                        </div>

                        {{-- REGISTRANT ID SECTION --}}
<!-- {{--
                        @foreach($eventversion->instrumentations() AS $instrumentation)
                            <div style="border-bottom: 1px solid lightgrey;padding-bottom: .5rem;">
                                <label style="font-weight: bold; margin-top: .5rem;">{{ strtoupper($instrumentation->descr) }} </label>
                                <div style="display: flex; flex-direction: row; flex-wrap: wrap;">
                                    @foreach($registrants AS $registrant)
                                        @if($registrant->instrumentations->count() && $registrant->instrumentations->first()->id === $instrumentation->id)
                                            <div style="background-color: {{ $registrant->tabroomStatusBackgroundColor() }}; border: 1px solid black; border-radius: .25rem; margin-right: .25rem; margin-bottom: .25rem; padding: 0 .1rem"
                                                title="{!! $registrant->auditionDetails() !!}">
                                                {{ $registrant->id }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
--}} -->
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection





