<div>

    <x-modals.emailreceiptmodal :payerschool="$payerschool" :emailbody="$emailbody" />

    <table id="rm_table">
        <thead>
        <tr>
            <th>###</th>
            <th>School Name</th>
            <th>Receipt</th>
            <th>Eligible</th>
            <th>Applied</th>
            <th>Regist'd</th>
            @foreach($eventversion->instrumentations() AS $instrumentation)
                <th>{{ strtoupper($instrumentation->abbr) }}</th>
            @endforeach
            <th>Total</th>
            <th>Due</th>
            <th>Paid</th>
        </tr>
        </thead>
        <tbody>

        {{-- SUMMARY ROW --}}
        <tr style="background-color: lightgray;">
            <th colspan="2" class="text-right">Totals</th>
            <td></td>
            <th class="text-center">{{ $registrationactivity->eligibleTotal()->count() }}</th>
            <th class="text-center">{{ $registrationactivity->appliedTotal()->count() }}</th>
            <th class="text-center">{{ $registrationactivity->registeredTotal()->count() }}</th>
            @foreach($eventversion->instrumentations() AS $instrumentation)
                <th style="text-align: center;">
                    {!! $registrationactivity->registeredInstrumentationTotal($instrumentation)->count() !!}
                </th>
            @endforeach
            <th style="text-align: center;">
                {!! $registrationactivity->registeredTotal()->count() !!}
            </th>

            <th style="text-align: right; padding: 0 .25rem;">
                {{ number_format($registrationactivity->registrationfeeDueTotal(), 0) }}
            </th>

            <th style="text-align: right; padding: 0 .25rem;">
                {{ number_format($registrationactivity->registrationfeePaidTotalBySchools(), 0) }}
            </th>
        </tr>

        {{-- DETAIL ROWS --}}
        @foreach($schools AS $school)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><span title="{{ $school->shortName }}">
                        @if(config('app.url') === 'http://afdc2021.test')
                            <a href="{{ route('registrants.school.show', ['school' => $school]) }}"
                                title="Click for {{ $school->shortName }} registrants"
                            >
                                {{ substr($school->shortName, 0, 20) }} ({{ substr($school->county->name, 0, 3) }})
                            </a>
                        @else
                            <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/registrants/school/{{$school->id}}" title="Click for {{ $school->shortName }} registrants">
                                {{ substr($school->shortName, 0, 20) }} ({{ substr($school->county->name, 0, 3) }})
                            </a>
                        @endif
                        <br />
                        @if($school->currentTeacher && $school->currentTeacher->user_id)
                            <a href="mailto:{{ $school->currentTeacher->person->subscriberemailwork }}?subject={{$eventversion->name}}&body=Hi,{{$school->currentTeacher->person->first}}">
                                {{ $school->currentTeacher->person->fullName() }}
                            </a>
                        @endif
                    </span>
                </td>
                <td style="text-align: center; color: blue; cursor: pointer;">
                    <span wire:click="setSchool({{ $school }})">
                        email
                    </span>
                </td>
                <td style="text-align: center;">{!! $registrationactivity->eligibleCount($school) !!}</td>
                <td style="text-align: center;">{!! $registrationactivity->appliedCount($school) !!}</td>
                <td style="text-align: center;">{!! $registrationactivity->registeredCount($school) !!}</td>
                @foreach($eventversion->instrumentations() AS $instrumentation)
                    <td style="text-align: center;">
                        {!! $registrationactivity->registeredInstrumentationCount($school, $instrumentation) !!}
                    </td>
                @endforeach
                <td style="text-align: center;">{!! $registrationactivity->registeredCount($school) !!}</td>
                <td style="text-align: right; padding: 0 .25rem;">
                    {{ number_format($registrationactivity->registrationFeeDue($school), 0 ) }}
                </td>
                <th style="text-align: right; padding: 0 .25rem;">
                    {{ number_format($registrationactivity->registrationfeePaidBySchool($school), 0) }}
                </th>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
