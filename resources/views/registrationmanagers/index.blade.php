@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Registration Manager') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    {{-- COUNTY SCOPE --}}
                    <x-navs.togglecounties toggle="{{$toggle}}" :counties="$counties" :mycounties="$mycounties"/>

                    <div style="margin:auto;">
                        <x-tables.schoolscounties
                            toggle="{{ $toggle }}"
                            :counties="$counties"
                            :eventversion="$eventversion"
                            :mycounties="$mycounties"
                            :myschools="$myschools"
                            :registrationactivity="$registrationactivity"
                            :schools="$schools"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

