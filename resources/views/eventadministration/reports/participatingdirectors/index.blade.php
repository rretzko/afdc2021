@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

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
                            @foreach($eventversion->eventensembles->get() AS $ensemble)
                                <h3>{{ $ensemble->name }}</h3>

                                <table>
                                    <thead>
                                        <tr>
                                            <th>###</th>
                                            <th>Name</th>
                                            <th>School</th>
                                            <th>Emails</th>
                                            <th>Phones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ensemble->participatingTeachers($eventversion) AS $teacher)
                                            <tr>
                                               <td>{{ $loop->iteration }}</td>
                                                <td style="text-align: left;">
                                                    {{ $teacher->person->fullnameAlpha() }}
                                                </td>
                                                <td>
                                                    {{ $teacher->schools->first()->name }}
                                                </td>
                                                <td>
                                                    {!! str_replace(',', '<br />',$teacher->person->subscriberEmailsCsv) !!}
                                                </td>
                                                <td>
                                                    {!!  str_replace(',','<br />', $teacher->person->subscriberPhoneCsv) !!}
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
