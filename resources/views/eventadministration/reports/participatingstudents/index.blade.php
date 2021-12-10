@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout :event="$eventversion->event" :eventversion="$eventversion" />

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Event Administration: Participating Students for ').$eventversion->name }}
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
                                Participating Students
                            </h4>
                            @foreach($eventversion->eventensembles->get() AS $ensemble)
                                <h3>{{ $ensemble->name }}</h3>

                                <div>
                                    @if(config('app.url') === 'http://afdc2021.test')
                                        <a href="{{ route('eventadministrator.tabroom.reports.participatingstudents.csv',
                                            [
                                                'eventversion' => $eventversion,
                                                'eventensemble' => $ensemble
                                            ]) }}"
                                        >
                                            Download csv
                                        </a>
                                    @else
                                        <a href="https://afdc-2021-l38q8.ondigitalocean.app/eventadministrator/tabroom/reports/participatingstudents/csv/{{ $eventversion->id }}/{{ $eventensemble->id }}"
                                        >
                                            Download csv
                                        </a>
                                    @endif
                                </div>

                                <table>
                                    <thead>
                                    <tr>
                                        <th>###</th>
                                        <th>Name</th>
                                        <th>Voice Part</th>
                                        <th>Height</th>
                                        <th>FootInches</th>
                                        <th>Score</th>
                                        <th>School</th>
                                        <th>Teacher</th>
                                        <th>Emails</th>
                                        <th>Phones</th>
                                        <th>Guardians</th>
                                        <th>Guardian Emails</th>
                                        <th>Guardian Phones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ensemble->participatingRegistrants($eventversion) AS $registrant)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="text-align: left;">
                                                {{ $registrant->student->person->fullnameAlpha() }}
                                            </td>
                                            <td>
                                                {{ strtoupper($registrant->instrumentations->first()->abbr) }}
                                            </td>
                                            <td>
                                                {{ $registrant->student->height }}
                                            </td>
                                            <td>
                                                {{ $registrant->student->heightFootInch }}
                                            </td>
                                            <td>
                                                {{ $registrant->grandtotal() }}
                                            </td>
                                            <td style="text-align: left;">
                                                {{ $registrant->student->currentSchool->shortName }}
                                            </td>
                                            <td style="text-align: left;">
                                                {{ $registrant->student->currentTeacher->person->fullName() }}
                                            </td>

                                            <td>
                                                {!! str_replace(',', '<br />',$registrant->student->emailsCsv) !!}
                                            </td>

                                            <td>
                                                {!!  str_replace(',','<br />', $registrant->student->phonesCsv) !!}
                                            </td>
                                            <td>
                                                @forelse($registrant->student->guardians AS $guardian)
                                                        {{ $guardian->person->fullName() }}
                                                @empty
                                                    No guardian found
                                                @endforelse
                                            </td>
                                            <td>
                                                @forelse($registrant->student->guardians AS $guardian)
                                                    {!!  str_replace(',','<br />', $guardian->emailCsv) !!}
                                                @empty
                                                    No guardian emails found
                                                @endforelse
                                            </td>

                                            <td>
                                                @forelse($registrant->student->guardians AS $guardian)
                                                    {!!  str_replace(',','<br />', $guardian->phoneCsv) !!}
                                                @empty
                                                    No guardian phones found
                                                @endforelse
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
