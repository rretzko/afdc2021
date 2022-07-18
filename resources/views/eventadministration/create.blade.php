@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12">

                <x-logout :event="null" :eventversion="null" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Eventversion Administration: Add A New Event') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem;">
                        <div>
                            <h4>
                                Add A New Event
                            </h4>

                            <ul>
                                <li>audition count</li>
                                <li>frequency</li>
                                <li>grades</li>
                                <li>status</li>
                                <li>first event</li>
                                <li>logo file</li>
                                <li>log_file_alt</li>
                                <li>requiredheight</li>
                                <li>requiredshirtsize</li>
                            </ul>

                            <form method="post" action="{{ route('event.store') }}" >

                                @csrf

                                <style>
                                    label{font-weight: bold;}
                                </style>
                                {{-- NAME --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" value="" style="width: 100%; max-width: 40rem; @error('name') background-color: rgba(255,0,0,0.1); @enderror"  required />
                                    @error('name')
                                        <div style="color: darkred;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- SHORTNAME --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="shortname">Short Name</label>
                                    <input type="text" name="shortname" id="shortname" value="" style="width: 100%; max-width: 40rem; @error('shortname') background-color: rgba(255,0,0,0.1); @enderror"  required />
                                    @error('shortname')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- ORGANIZATION --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="organization_id">Organization</label>
                                    <select name="organization_id" style="width: 100%; max-width: 40rem;">
                                        @foreach($organizations AS $organization)
                                            <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('organization_id')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- AUDITION COUNT --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="auditioncount">Audition Count</label>
                                    <div style="margin-left: 2rem;">
                                        <div>
                                            <input type="radio" name="auditioncount" id="auditioncount" value="1" checked />
                                            <label style="font-weight: normal;">There is a limit to the number of registrants allowed.</label>
                                        </div>
                                        <div style="margin-top: -0.66rem;">
                                            <input type="radio" name="auditioncount" id="auditioncount" value="0" />
                                            <label style="font-weight: normal;">Unlimited number of registrants is allowed.</label>
                                        </div>
                                    </div>
                                    @error('organization')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- SUBMIT --}}
                                <div>
                                    <input type="submit" name="submit" id="submit" value="Submit" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
