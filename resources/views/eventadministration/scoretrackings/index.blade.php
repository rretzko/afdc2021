@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

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

                        {{-- DEFINITION --}}
                        <div id="definition">
                            <div style="font-style: italic; text-align: center; margin-bottom: 1rem;">
                                @if(($eventversion->event->id === 11) || ($eventversion->event->id === 12))
                                    def. A teacher is deemed 'participating' if the teacher has acknowledged the Obligations page.
                                @else
                                    def. A teacher is deemed 'participating' if the teacher has confirmed a registrant's application signatures.
                                @endif
                            </div>
                        </div>

                        {{-- REGISTRANT ID SECTION --}}
                        @foreach($eventversion->instrumentations() AS $instrumentation)
                            <div style="border-bottom: 1px solid lightgrey;padding-bottom: .5rem;">
                                <label style="font-weight: bold; margin-top: .5rem;">{{ strtoupper($instrumentation->descr) }} </label>
                                <div style="display: flex; flex-direction: row; flex-wrap: wrap;">
                                    @foreach($registrants AS $registrant)
                                        @if($registrant->instrumentations->first()->id === $instrumentation->id)
                                            <div style="background-color: {{ $registrant->tabroomStatusBackgroundColor() }}; border: 1px solid black; border-radius: .25rem; margin-right: .25rem; margin-bottom: .25rem; padding: 0 .1rem">
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





