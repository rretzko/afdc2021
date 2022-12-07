@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Rehearsal Manager: $eventversion->name Participation Fees") }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: column; justify-content: space-around">

                        {{-- DEFINITION --}}
                        <div id="definition">
                            <div style="font-style: italic; text-align: center; margin-bottom: 1rem;">
                                def. A school is deemed 'participating' if it has students accepted to the event's ensembles.
                            </div>
                        </div>

                        {{-- SCHOOL TABLE --}}
                        <div>
                            <style>
                                table{border-collapse: collapse;margin: auto;}
                                td,th{border: 1px solid black; padding: 0 0.25rem;}
                            </style>
                            <table>
                                <thead>
                                <tr>
                                    <td colspan="5" style="border: 1px solid white; border-bottom: 1px solid black;"></td>
                                    <td style="border: 1px solid white; border-bottom: 1px solid black; font-size: small;">
                                        <a href="{{ route("rehearsalmanager.participationfee.export") }}">
                                            Download csv
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align: right;">Totals</th>
                                    <th style="text-align: center;">{{ $count_total }}</th>
                                    <th style="text-align: center;">{{ $sum_students }}</th>
                                    <th style="text-align: center;">{{ $sum_teachers }}</th>
                                    <th style="text-align: center;">{{ $sum_balance_due }}</th>
                                </tr>
                                <tr>
                                    <th>###</th>
                                    <th>School</th>
                                    <th>Students</th>
                                    <th>PayPal Students</th>
                                    <th>PayPal Teacher</th>
                                    <th>Balance Due</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($acceptances AS $accepted)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $accepted['name'] }}</td>
                                        <td style="text-align: center;">{{ $accepted['count'] }}</td>
                                        <td style="text-align: center;">{{ $accepted['paypal_students'] }}</td>
                                        <td style="text-align: center;">{{ $accepted['paypal_teacher'] }}</td>
                                        <td style="text-align: center;">{{ $accepted['balance_due'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




