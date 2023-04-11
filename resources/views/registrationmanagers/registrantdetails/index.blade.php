@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Registration Manager: Registrant Details: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section id="header" style="padding: 1rem;">
                        <div class="input-group" style="display: flex; flex-direction: row; justify-content: space-around;">
                            <label for="instrumentation_id"></label>
<div style="display:flex; flex-wrap: wrap; justify-content: space-evenly; background-color: rgba(0,0,0,0.1); padding: 0.25rem;border-radius: 0.25rem; border: 1px solid darkgray;">

                            @foreach($navInstrumentations AS $key => $nav)

                                <a href="{{ route('registrationmanagers.registrantdetails.show',['eventversion' => $eventversion, 'instrumentation' => $key]) }}"                                    style="padding: 0 0.2rem; margin: 0.1rem 0; border: 1px solid darkgray; border-radius: 0.25rem; background-color: white;" >
                                    {{ strtoupper($nav[0]) }} ({{ $nav[1] }})
                                </a>
                            @endforeach
</div>
                            <div id="selectStudent" style="width: 100%; margin-top: 1rem;">

                                {{-- SUCCESS MESSAGE --}}
                                @if(session()->has('success') && strlen(session()->get('success')))
                                    <div style=" background-color: rgba(0,255,0,0.1); border: 1px solid darkgreen; width: 50%; margin-left: 25%; margin-bottom: 0.5rem; padding: 0 0.25rem;">
                                        {{ session()->get('success') }}

                                        {{ session()->remove('success') }}
                                    </div>
                                @endif

                                <form method="post" action="{{ route('registrationmanagers.registrantdetail.registrant', ['eventversion' => $eventversion ]) }}" style="display: flex; flex-direction: row; margin: 0 25%;">

                                    @csrf

                                    <div class="input-group" style="display: flex; flex-direction: row;">
                                        <label style="margin-right: 1rem;"></label>
                                        <select name="id" autofocus>
                                            <option value="0">- Select Student --</option>
                                            @foreach($selectRegistrants AS $registrant)
                                                <option value="{{ $registrant['id'] }}" >
                                                    {{ $registrant['fullNameAlpha'] }}
                                                </option>
                                            @endforeach
                                            <option value="0">- Select -</option>
                                        </select>
                                    </div>

                                    <div class="input-group">
                                        <label></label>
                                        <input type="submit" name="submit" value="Get Student" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>

                    <section id="cards" style="padding: 0 .5rem;">
                        @if($targetinstrumentation)

                            <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                <h2>{{ strtoupper($targetinstrumentation->descr) }}</h2>

                                <div>

                                 {{--   @if(config('app.url') === 'http://afdc2021.test') --}}
                                        <a href="{{ route('registrationmanagers.registrantdetails.csv',
                                            [
                                                'eventversion' => $eventversion,
                                                'instrumentation' => $targetinstrumentation,
                                            ]) }}"
                                        >
                                            Print CSV
                                        </a>
                                    {{-- @else
                                        <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanagers/registrantdetails/csv/{{ $eventversion->id }}/{{ $targetinstrumentation->id }}">
                                            Print CSV
                                        </a>
                                    @endif --}}
                                </div>
                            </div>
                            <div style="">

                                <x-registrantdetails.registrantdetail
                                    :eventversion="$eventversion"
                                    :instrumentation="$targetinstrumentation"
                                    :registrants="$registrantsArray"
                                />

                            </div>

                        @endif

                        {{-- TARGET REGISTRANT --}}
                        @if(isset($targetRegistrant) && $targetRegistrant)

                            <div style="width: 100%;">

                                <style>
                                    label{width: 6rem;}
                                    .divHeader{text-transform: uppercase; font-weight: bold; font-size: 0.8rem;margin: 0.5rem 0;}
                                    .input-group{margin-bottom: 0.25rem;}
                                </style>
                                <form method="post" action="{{ route('registrationmanagers.registrantdetail.registrant.update') }}" style="margin-left: 25%; background-color: rgba(0,0,0,0.1); padding: 0.25rem; border: 1px solid black;">

                                    @csrf

                                    <input type="hidden" name="id" value="{{ $targetRegistrant['id'] }}" />
                                    <input type="hidden" name="guardianId" value="{{ $targetRegistrant['guardianId'] }}" />

                                    <h4>You are editing the student record of: <b>{{ $targetRegistrant['fullNameAlpha'] }}</b></h4>

                                    <div class="input-group">
                                        <label for="first" style="margin-right: 0.5rem;">Name</label>
                                        <div style="display:flex; flex-direction: row; " class="space-x-2";>
                                            <input type="text" style="margin-right: 0.25rem;" name="first" value="{{ $targetRegistrant['first'] }}" />
                                            <input type="text" style="margin-right: 0.25rem;" name="middle" value="{{ $targetRegistrant['middle'] }}" />
                                            <input type="text" style="margin-right: 0.25rem;" name="last" value="{{ $targetRegistrant['last'] }}" />
                                        </div>
                                    </div>

                                    <div class="input-group">
                                        <label for="instrumentation_id" style="margin-right: 0.5rem;">Voice Part</label>
                                        <select name="instrumentation_id">
                                            @foreach($navInstrumentations AS $id => $details)
                                                <option value="{{ $id }}"
                                                    @if($targetRegistrant['instrumentation_id'] == $id) selected @endif
                                                >
                                                    {{ $details[0] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="divHeader">Student Phones</div>
                                    <div class="input-group">

                                        <label for="phoneHome">Home Phone</label>
                                        <input type="text" name="phoneHome" value="{{ $targetRegistrant['phoneHome'] }}" />
                                    </div>

                                    <div class="input-group">

                                        <label for="phoneMobile">Cell Phone</label>
                                        <input type="text" name="phoneMobile" value="{{ $targetRegistrant['phoneMobile'] }}" />
                                    </div>

                                    <div class="divHeader">Parent/Guardian Phones</div>
                                    <div class="input-group">

                                        <label for="phoneHome">Home Phone</label>
                                        <input type="text" name="guardianHome" value="{{ $targetRegistrant['guardianHome'] }}" />
                                    </div>

                                    <div class="input-group">

                                        <label for="phoneMobile">Cell Phone</label>
                                        <input type="text" name="guardianMobile" value="{{ $targetRegistrant['guardianMobile'] }}" />
                                    </div>

                                    <div class="input-group">

                                        <label for="guardianWork">Work Phone</label>
                                        <input type="text" name="guardianWork" value="{{ $targetRegistrant['guardianWork'] }}" />
                                    </div>

                                    <div class="input-group">

                                        <label></label>
                                        <input type="submit" name="submit" value="Update Record" />
                                    </div>


                                </form>

                            </div>
                        @endif

                    </section>

                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
@endsection


