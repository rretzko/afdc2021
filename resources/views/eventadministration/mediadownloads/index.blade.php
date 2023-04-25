@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Media Download') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    {{-- REGISTRANT ID FORM --}}
                    <div style="width: 50%; margin: auto; margin-top: 1rem;">
                        <style>
                            label{width: 7rem;}
                            .input-group{margin-bottom: 0.25rem;}
                        </style>
                        <form method="post" action="{{ route('eventadministrator.media.download') }}">

                            @csrf

                            <div class="input-group">
                                <label for="registrant_id" style="">Registrant Id</label>
                                <input type="text" name="registrant_id" value=""/>
                            </div>
                            @error('registrant_id')

                            <div class="input-group">
                                <label> </label>
                                <div style="color: red; font-size: smaller;">{{ $message }}</div>
                            </div>
                            @enderror
                            <div class="input-group">
                                <label> </label>
                                <input type="submit" name="submit" value="Get Files" />
                            </div>
                        </form>
                    </div>

                    <div style="display: flex; flex-direction: row;">
                        {{-- REGISTRANT ID BUBBLES --}}
                        <div style="padding:1rem; display:flex; flex-direction: column; justify-content: space-around">

                            {{-- REGISTRANT ID SECTION --}}
{{--
                            @foreach($eventversion->instrumentations() AS $instrumentation)
                                <div style="border-bottom: 1px solid lightgrey;padding-bottom: .5rem;">
                                    <label style="font-weight: bold; margin-top: .5rem;">{{ strtoupper($instrumentation->descr) }} </label>
                                    <div style="display: flex; flex-direction: row; flex-wrap: wrap;">
                                        @foreach($registrants AS $registrant)
                                            @if($registrant->instrumentations->first()->id === $instrumentation->id)
                                                <a href=" {{ route('eventadministrator.media.download',['registrant' => $registrant]) }}" style=" border: 1px solid black; border-radius: .25rem; margin-right: .25rem; margin-bottom: .25rem; padding: 0 .1rem"
                                                     title="{!! $registrant->auditionDetails() !!}">
                                                    {{ $registrant->id }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                        </div>
--}}
                        {{-- VIEWPORTS --}}
                        <div>
                            @if($targetregistrant->id)
                            <h3>Viewports for: {{ $targetregistrant->id }}</h3>
                                @foreach($eventversion->filecontenttypes AS $filecontenttype)
                                    <div>

                                            <label>{{ ucwords($filecontenttype->descr) }}</label>
                                            <div style="text-align: center;" >
                                                {!! $targetregistrant->fileviewport($filecontenttype) !!}
                                            </div>

                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection





