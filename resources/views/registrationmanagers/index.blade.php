@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Registration Manager: $eventversion->name ") }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    {{-- COUNTY SCOPE --}}
                    @if(count($mycounties))
                        <x-navs.togglecounties toggle="{{$toggle}}" :counties="$counties" :mycounties="$mycounties"/>
                    @endif

                    {{-- ACTIVITY NAVIGATION MENU --}}
                    <x-navs.activities toggle="{{ $toggle }}"
                                        :counties="$counties"
                                        :mycounties="$mycounties"
                                        :eventversion="$eventversion"
                    />

                    <div style="margin:auto;">
                        {!! $table !!}
                    </div>

                    {{--
                    <div style="margin:auto;">
                        <x-tables.schoolscounties toggle="{{ $toggle }}" :eventversion="$eventversion" />
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
@endsection

