@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Participating Directors for ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding: 1rem .5rem;">
                        <style>
                            table{text-align: center; margin-bottom: 1rem; border-collapse: collapse;}
                            td,th{border:1px solid black; padding: 0 .25rem;}
                        </style>
                        <div>
                            <h4>
                                Participating Directors
                            </h4>
                            @foreach($participating AS $key => $registrants)
                                <h3>{{ $key }}</h3>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>###</th>
                                            <th>Name</th>
                                            <th>Voice</th>
                                            <th>Emails</th>
                                            <th>Teacher</th>
                                            <th>School</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($registrants AS $registrant)
                                            <tr>
                                               <td>{{ $loop->iteration }}</td>
                                                <td style="text-align: left;">
                                                    {{ $registrant->student->person->fullnameAlpha() }}
                                                </td>
                                                <td>
                                                    {{ strtoupper($registrant->instrumentations->first()->abbr) }}
                                                </td>
                                                <td>
                                                    {{ $registrant->student->emailsCsv }}
                                                </td>
                                                <td>
                                                    {{ $registrant->student->currentTeacher }}
                                                </td>
                                                <td>
                                                    {{ $registrant->student->currentSchool->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
