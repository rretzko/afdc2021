@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$event" :eventversion="$event"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <section style="margin: auto; padding: 1rem 0; ">

                        <style>
                            label{width: 10rem;}
                            .data{font-weight: bold;}
                            .stat{display: flex; flex-direction: row; margin-left: 1rem;}
                        </style>

                        <div>
                            <h2>Event Dashboard : {{ $event->name }}</h2>

                            <h5>Directors</h5>

                            <div class="stats">

                                {{-- INVITED DIRECTORS --}}
                                <div class="stat">
                                    <label>Invited</label>
                                    <div class="data">
                                        {{ $dashboard->invitedDirectors() }}
                                    </div>
                                </div>

                                {{-- OBLIGATED DIRECTORS --}}
                                <div class="stat">
                                    <label title="Directors who have clicked through the Obligation page">
                                        Obligated
                                    </label>
                                    <div class="data">
                                        {{ $dashboard->obligatedDirectors() }}
                                    </div>
                                </div>
                            </div>

                            <h5>Students</h5>
                            <div>

                                {{-- SIGNED APPLICATIONS --}}
                                <div class="stat">
                                    <label>Signed Applications</label>
                                    <div class="data">
                                        {{ $dashboard->signedApplications() }}
                                    </div>
                                </div>

                                {{-- REGISTERED STUDENTS --}}
                                <div class="stat">
                                    <label>Registered Students</label>
                                    <div class="data">
                                        {{ $dashboard->registeredStudentsCount($event) }}
                                    </div>
                                </div>

                                {{-- VIRTUAL AUDITION --}}
                                @if($event->eventversionconfig->virtualaudition)
                                    {{-- AT LEAST ONE UPLOADED FILE --}}
                                    <div class="stat">
                                        <label>An Uploaded File</label>
                                        <div class="data">
                                            {{ $dashboard->atLeastOneUploadedFile() }}
                                        </div>
                                    </div>

                                    {{-- ALL UPLOADED FILES --}}
                                    <div class="stat">
                                        <label>All Uploaded Files</label>
                                        <div class="data">
                                            {{ $dashboard->allUploadedFiles() }}
                                        </div>
                                    </div>

                                    {{-- ALL UPLOADED FILES APPROVED --}}
                                    <div class="stat">
                                        <label>All Files Approved</label>
                                        <div class="data">
                                            {{ $dashboard->allFilesApproved() }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </section>

                </div>
            </div>

        </div>
    </div>

@endsection


