@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Event Administration: $eventversion->name  Student Counts") }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: column; justify-content: space-around">

                        <style>
                            table{border-collapse: collapse;}
                            td,th{border: 1px solid black; padding-left: .25rem; padding-right: .25rem; text-align: center;}
                            td.label{width: 30%; text-align: right;}
                            td.highlight{background-color: rgba(0,0,0,0.1); font-weight: bold;}
                        </style>
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">Total</th>
                                    @foreach($eventversion->instrumentations() AS $instrumentation)
                                        <th class="text-center" >{{ strtoupper($instrumentation->abbr) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="label">Schools with applicants</td>
                                <td>{{ $schools }}</td>
                                <td colspan="{{ $eventversion->instrumentations()->count() }}"></td>
                            </tr>
                            <tr>
                                <td class="label">Applicants</td>
                                <td>{{ $applicants }}</td>
                                @foreach($eventversion->instrumentations() AS $instrumentation)
                                    <td>{{ $applicantsbyinstrumentation[$instrumentation->id] }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="label highlight" >Registrants</td>
                                <td class="highlight">{{ $registrants }}</td>
                                @foreach($eventversion->instrumentations() AS $instrumentation)
                                    <td class="highlight">{{ $registrantsbyinstrumentation[$instrumentation->id] }}</td>
                                @endforeach
                            </tr>
                            @if($eventversion->eventversionconfig->virtualaudition)
                                <tr>
                                    <td class="label">At least one recording uploaded</td>
                                    <td>{{ $minrecording }}</td>
                                    @foreach($eventversion->instrumentations() AS $instrumentation)
                                        <td>{{ $minrecordingbyinstrumentation[$instrumentation->id] }}</td>
                                    @endforeach
                                </tr>
                            @endif
                            @if($eventversion->eventversionconfig->virtualaudition)
                                <tr>
                                    <td class="label">Full set of recording uploaded & approved</td>
                                    <td>{{ $fullrecordings }}</td>
                                    @foreach($eventversion->instrumentations() AS $instrumentation)
                                        <td>{{ array_key_exists($instrumentation->id, $fullrecordingsbyinstrumentation) ? $fullrecordingsbyinstrumentation[$instrumentation->id] : 0 }}</td>
                                    @endforeach
                                </tr>
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




