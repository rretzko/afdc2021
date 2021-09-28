@props([
'counties',
'eventversion',
'mycounties',
'myschools',
'registrationactivity',
'schools',
'toggle',
])
<div style="display: none;">{{ set_time_limit(90) }}</div>
<style>
    #rm_table{ border-collapse: collapse;}
    #rm_table td,th{border: 1px solid black; padding:0 .25rem;}
</style>
<table id="rm_table">
    <thead>
    <tr>
        <th>###</th>
        <th>School Name</th>
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
                {{ number_format($registrationactivity->registrationfeePaidTotal(), 0) }}
            </th>
        </tr>

        {{-- DETAIL ROWS --}}
        @foreach((($toggle === 'my') ? $myschools : $schools) AS $school)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><span title="{{ $school->shortName }}">
                        {{ substr($school->shortName, 0, 20) }} ({{ substr($school->county->name, 0, 3) }})
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
                    {{ number_format($registrationactivity->registrationfeePaid($school), 0) }}
                </th>
            </tr>
        @endforeach
    </tbody>
</table>

