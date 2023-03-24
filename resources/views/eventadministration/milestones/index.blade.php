@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __("Event Administration: $eventversion->name Milestones") }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="padding:1rem; display:flex; flex-direction: column; justify-content: space-around">

                        {{-- DEFINITION --}}
                        <div id="definition">
                            <div style="font-style: italic; text-align: center; margin-bottom: 1rem;">
                                def. This page provides event milestone details
                            </div>
                        </div>

                        {{-- MILESTONE TABLE --}}
                        <div>
                            <style>
                                table{border-collapse: collapse;}
                                td,th{border: 1px solid black;text-align: center;}
                            </style>
                            <table>
                                <thead>
                                <tr>
                                    <td colspan="2">
                                        <a href="">
                                            Download csv
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>###</td>
                                    <td>User ID</td>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($inviteds AS $invited)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $invited['user_id'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">None Found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




