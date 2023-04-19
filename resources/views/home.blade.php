@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section style="margin: auto; padding: 1rem 0; ">

                        {{-- EVENT ADMINISTRATOR --}}
                        <div>
                            @if($eventroles->count())
                                <h2>Event Administration</h2>
                                @if(auth()->id() === 368)
                                    <div>
                                        <a href="{{ route('event.create') }}">
                                            Add New Event
                                        </a>
                                    </div>
                                @endif
                                <div>
                                    @foreach($eventroles AS $eventrole)
                                        <div style="font-size: 1.15rem;">
                                            <a href="{{route("eventadministration.index",
                                                [
                                                  'event' => $eventrole->event(),
                                                ])}}">
                                                    {{$eventrole->event()->name}}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- SYSTEM ADMINISTRATION --}}
                            @if(auth()->id() === 368)

                                {{-- LOG IN AS --}}
                                <div style="border-top: 1px solid darkblue;">
                                    <a href="{{ route('sa.loginas.edit') }}">
                                        Log In As
                                    </a>
                                </div>

                                {{-- PAYPAL MANUAL ENTRY --}}
                                <div style="">
                                    <a href="{{ route('sa.paypals.edit') }}">
                                        PayPal Update Check
                                    </a>
                                </div>

                                {{-- LOAD FAKE SCORES FOR TESTING --}}
                                <div style="">
                                    <a href="{{ route('sa.loadscores.edit') }}">
                                        Load Fake Scores For Testing
                                    </a>
                                </div>

                                    {{-- SCORE SUMMARY LOAD --}}
                                    <div style="">
                                        <a href="{{ route('sa.loadscoresummaries.index') }}">
                                            Load Score Summaries from pre-loaded scores
                                        </a>
                                    </div>
                            @endif
                        </div>

                    </section>

                </div>
            </div>

        </div>
    </div>

    @if(auth()->id() == 343) {{-- olivia dunn --}}
    {{ phpinfo() }}
    @endif

@endsection


