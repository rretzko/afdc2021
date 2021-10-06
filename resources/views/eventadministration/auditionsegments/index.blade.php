@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Audition Segments') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem;">
                        <form method="post" action="{{ route('eventadministrator.segments.update') }}">
                            @csrf

                            {{-- CHECKBOXES --}}
                            <div style="display: flex; flex-direction: column; padding-left: .5rem">
                                @foreach($filecontenttypes AS $filecontenttype)
                                    <div class="input-group" style="">
                                        <div style="padding-top: .1rem;">
                                            <input type="checkbox"
                                                   name="filecontenttypes[]"
                                                   id="filecontenttype_{{ $filecontenttype->id }}"
                                                   value="{{ $filecontenttype->id }}"
                                                   @if($currentfilecontenttypes->contains($filecontenttype))
                                                       CHECKED
                                                    @endif
                                            />
                                        </div>
                                        <label for="filecontenttype_{{ $filecontenttype->id }}" style="margin-left: .5rem;">
                                            {{ $filecontenttype->descr }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="input-group">
                                <input type="submit" name="submit" id="submit" value="Update Segments" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
