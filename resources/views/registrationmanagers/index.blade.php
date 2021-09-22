@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <x-logout />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Registration Manager') }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    {{-- COUNTY SCOPE --}}
                    <div style="display:flex; justify-content: space-evenly;";>
                        <div>
                            <a href="{{ route('registrationmanager.show',['counties' => 'my']) }}">My Counties ({{ count($mycounties) }})</a>
                        </div>
                        <div>
                            <a href="{{ route('registrationmanager.show',['counties' => 'all']) }}">All Counties ({{ count($counties) }})</a>
                        </div>
                    </div>
                    <div>
                        <table>
                            <thead>
                            <tr>
                                <th>###</th>
                                <th>School Name</th>
                            </tr>
                            </thead>
                            @foreach((($toggle === 'my') ? $myschools : $schools) AS $school)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td><span title="{{ $school->shortName }}">
                                            {{ substr($school->shortName, 0, 20) }} ({{$school->id}})
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

