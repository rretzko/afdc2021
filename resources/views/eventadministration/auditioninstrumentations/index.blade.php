@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Event Administration: $eventversion->name : Audition Instrumentation and Voice Parts") }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem;">
                        <form method="post" action="{{ route('eventadministrator.instrumentations.update') }}">
                            @csrf

                            {{-- CHECKBOXES --}}
                            <div style="display: flex; flex-direction: column; padding-left: .5rem">
                                @foreach($instrumentations AS $instrumentation)
                                    <div class="input-group" style="">
                                        <div style="padding-top: .1rem;">
                                            <input type="checkbox"
                                                   name="instrumentations[]"
                                                   id="instrumetation_{{ $instrumentation->id }}"
                                                   value="{{ $instrumentation->id }}"
                                                   @if($currentinstrumentations->contains($instrumentation))
                                                   CHECKED
                                                @endif
                                            />
                                        </div>
                                        <label for="filecontenttype_{{ $instrumentation->id }}" style="margin-left: .5rem;">
                                            {{ $instrumentation->descr }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Instrumentation is tied to ensemble type & not updateable  --}}
                            <!--
                            <div class="input-group">
                                <input type="submit" name="submit" id="submit" value="Update Instrumentation and Voice Parts" />
                            </div>
                            -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

