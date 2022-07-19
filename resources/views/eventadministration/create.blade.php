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

                            <form method="post" action="{{ route('event.store') }}" >

                                @csrf

                                @if($errors->any())
                                    @foreach($errors->all() AS $error)
                                        <div style="border: 1px solid darkred; background-color: rgba(255,0,0,0.1); color: darkred; padding: 0 0.5rem;">
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @enderror

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

                                {{-- SHORT NAME --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="short_name">Short Name</label>
                                    <input type="text" name="short_name" id="short_name" value="" style="width: 100%; max-width: 40rem; @error('short_name') background-color: rgba(255,0,0,0.1); @enderror"  required />
                                    @error('short_name')
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
                                    @error('auditioncount')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- FREQUENCY --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="frequency">Frequency</label>
                                    <div style="margin-left: 2rem;">
                                        <div>
                                            <input type="radio" name="frequency" id="frequency" value="annual" checked />
                                            <label style="font-weight: normal;">Annual</label>
                                        </div>
                                        <div style="margin-top: -0.66rem;">
                                            <input type="radio" name="frequency" id="frequency" value="biennial" />
                                            <label style="font-weight: normal;">Biennial</label>
                                        </div>
                                    </div>
                                    @error('frequency')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- GRADES --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="grades">Grades</label>
                                    <div style="margin-left: 2rem;">
                                        <div style="display: flex; flex-direction: row; flex-wrap: wrap;">
                                            @foreach(\App\Models\Gradetype::orderBy('orderby')->get() AS $grade)
                                                <div style="margin-right: 1rem;">
                                                    <input type="checkbox" name="grades[]" id="grades[]" value="{{ $grade->id }}" >
                                                    <label for="" >{{ $grade->descr }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @error('grades')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- STATUS --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="status">Status</label>
                                    <div style="margin-left: 2rem;">
                                        <div>
                                            <input type="radio" name="status" id="status" value="sandbox" checked />
                                            <label style="font-weight: normal;">Sandbox</label>
                                        </div>
                                        <div style="margin-top: -0.66rem;">
                                            <input type="radio" name="status" id="status" value="active" />
                                            <label style="font-weight: normal;">Active</label>
                                        </div>
                                        <div style="margin-top: -0.66rem;">
                                            <input type="radio" name="status" id="status" value="inactive" />
                                            <label style="font-weight: normal;">Inactive</label>
                                        </div>
                                    </div>
                                    @error('status')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- REQUIRED HEIGHT --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="requiredheight">Height Required</label>
                                    <div style="margin-left: 2rem;">
                                        <div>
                                            <input type="radio" name="requiredheight" id="requiredheight" value="1" checked />
                                            <label style="font-weight: normal;">This event requires the registrant's height.</label>
                                        </div>
                                        <div style="margin-top: -0.66rem;">
                                            <input type="radio" name="requiredheight" id="requiredheight" value="0" />
                                            <label style="font-weight: normal;">Height is NOT a requirement for this event.</label>
                                        </div>
                                    </div>
                                    @error('requiredheight')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- REQUIRED SHIRT SIZE --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="requiredshirtsize">Shirt Size Required</label>
                                    <div style="margin-left: 2rem;">
                                        <div>
                                            <input type="radio" name="requiredshirtsize" id="requiredshirtsize" value="1" checked />
                                            <label style="font-weight: normal;">This event requires the registrant's shirt size.</label>
                                        </div>
                                        <div style="margin-top: -0.66rem;">
                                            <input type="radio" name="requiredshirtsize" id="requiredshirtsize" value="0" />
                                            <label style="font-weight: normal;">Shirt size is NOT a requirement for this event.</label>
                                        </div>
                                    </div>
                                    @error('requiredshirtsize')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- FIRST EVENT --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="first_event">First Event</label>
                                    <input type="text" name="first_event" id="first_event" value="" style="width: 100%; max-width: 10rem; @error('first_event') background-color: rgba(255,0,0,0.1); @enderror"  placeholder="Year of first event" required />
                                    @error('first_event')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- LOGO FILE --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="logo_file">Logo File Location</label>
                                    <input type="text" name="logo_file" id="logo_file" value="" style="width: 100%; max-width: 40rem; @error('logo_file') background-color: rgba(255,0,0,0.1); @enderror" />
                                    @error('logo_file')
                                    <div style="color: darkred;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                {{-- LOGO FILE ALT --}}
                                <div style="display: flex; flex-direction: column; margin-bottom: 0.25rem;">
                                    <label for="logo_file_alt">Logo File Alternate Tag</label>
                                    <input type="text" name="logo_file_alt" id="logo_file_alt" value="" style="width: 100%; max-width: 40rem; @error('logo_file_alt') background-color: rgba(255,0,0,0.1); @enderror" />
                                    @error('logo_file_alt')
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
