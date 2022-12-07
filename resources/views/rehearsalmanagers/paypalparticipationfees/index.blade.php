@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Rehearsal Manager: $eventversion->name PayPal Participation Fee Reconciliation") }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: column; justify-content: space-around">

                        {{-- DEFINITION --}}
                        <div id="definition">
                            <div style="font-style: italic; text-align: center; margin-bottom: 1rem;">
                                def. All PayPal payments for Participation Fees
                            </div>
                        </div>

                        {{-- PAYMENT TABLE --}}
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
                                        <a href="{{ route("rehearsalmanager.paypalreconciliation.export") }}">
                                            Download csv
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align: right;">Totals</th>
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center;"></th>
                                </tr>
                                <tr>
                                    <th>###</th>
                                    <th>Student/Teacher</th>
                                    <th>Registrant ID</th>
                                    <th>School</th>
                                    <th>Amount</th>
                                    <th>Process Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($participation_fees AS $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment['person']->fullnameAlpha() }}</td>
                                        <td>{{ $payment->registrant_id }}</td>
                                        <td style="text-align: center;">{{ $payment['school']->name }}</td>
                                        <td style="text-align: center;">{{ $payment->amount }}</td>
                                        <td style="text-align: center;">{{ $payment->updated_at }}</td>
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




