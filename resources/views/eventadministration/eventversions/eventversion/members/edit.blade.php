@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout  :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Eventversion Date Administration: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem; padding-bottom: 0;">

                        <h3>{{ $eventversion->name }} Members</h3>

                        {{-- ERRORS --}}
                        <div style="display: flex; flex-direction: column;">
                            @foreach($errors->all() AS $error)
                                <div style="color: red; padding:0.25rem; margin: 0.25rem 0;">{{ $error }}</div>
                            @endforeach
                        </div>

                        {{-- SUCCESS --}}
                        @if(Session::has('status'))
                            <div style="display: flex; flex-direction: column; background-color: rgba(0,255,0,0.1); padding: 0.25rem; margin: 0.25rem 0;">
                               {!! \Session::get('status') !!}
                            </div>
                        @endif

                        <form method="post" action="{{ route('eventadministration.eventversion.members.search') }}" >

                            @csrf

                            <style>

                            </style>

                            <div>
                                <label>Search</label>
                                <input type="text" name="search" value=""
                                       placeholder="by email, last name, or school name" style="min-width: 30rem;"
                                >
                            </div>
                            <div>
                                {{-- SUBMIT --}}
                                <div style="display: flex; flex-direction: column; max-width: 8rem; margin-left: 3.25rem; margin-bottom: 1rem;">
                                    <label></label>
                                    <input type="submit" name="submit" value="Search" />
                                </div>
                            </div>

                            {{-- MEMBERSHIP TABLE --}}
                            <div>
                                @if($matchesfound)

                                    {!! $matchesfound !!}

                                @endif
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@if(auth()->id() === 368) {{phpinfo()}} @endif
@endsection
