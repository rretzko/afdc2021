@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem;">
                        <div>
                            <h4>
                                Event Administration
                            </h4>
                            <ul>
                                <li>Judge Assignments
                                    <ul>
                                        <li>
                                            <a href="{{ route('eventadministrator.segments') }}"
                                               title="Define the major audition parts (ex. scales,solo,etc.">
                                                Audition Segments ({{ $eventversion->filecontenttypes->count() }})
                                            </a>
                                        </li>
                                        <li>Voice Parts</li>
                                        <li>Room Definitions</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4>
                                Tab Room
                            </h4>
                            <ul>
                                <li>Score Input</li>
                                <li>Registrant Updates</li>
                                <li>Reports</li>
                            </ul>
                        </div>
                        <ul>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
