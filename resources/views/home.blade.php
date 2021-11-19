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
                        </div>

                    </section>

                </div>
            </div>

        </div>
    </div>

@endsection


