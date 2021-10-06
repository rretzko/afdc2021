@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Adjudicator Assignments') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: row; justify-content: space-around">

                        {{-- ADJUDICATOR FORM --}}
                        <x-eventadministration.adjudicators.adjudicatorform
                            :member="$member"
                            :members="$members"
                            :rooms="$rooms"
                        />

                        {{-- ADJUDICATOR TABLE --}}


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



