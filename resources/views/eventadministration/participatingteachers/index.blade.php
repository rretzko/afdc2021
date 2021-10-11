@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Participating Teachers') }}
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

                        {{-- ADJUDICATOR TABLE --}}
                        <x-eventadministration.participatingteachers.participatingteacherstable
                            :eventversion="$eventversion"
                            :participatingteachers="$participatingteachers"
                        />

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




