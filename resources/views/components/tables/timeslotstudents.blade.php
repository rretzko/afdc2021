@props([
'eventversion',
'registrants',
])

<style>
    #rm_table{ border-collapse: collapse;}
    #rm_table tbody tr td,th{border: 1px solid red; padding:0 1rem;}
</style>

<div style="margin: 0.5rem 0;">
    @if(config('app.url') === 'http://afdc2021.test')
        <a href="{{ route('registrationmanagers.timeslotstudent.pdf', ['eventversion' => $eventversion]) }}">
            Download Pdf
        </a>
    @else
        <a href="http://afdc-2021-l28q8.ondigitalocean.app/registrationmanagers/timeslotstudent/pdf/{{ $eventversion->id }}">
            Download Pdf!
        </a>
    @endif
</div>

<table id="rm_table">
    <thead>
    <tr>
        <th>###</th>
        <th>Timeslot</th>
        <th>Reg.Id.</th>
        <th>Name</th>
        <th>School Name</th>
        <th>Voice Part</th>
    </tr>
    </thead>
    <tbody>
        @forelse($registrants AS $registrant)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td style="text-align: center;">{{ $registrant->timeslot }}</td>
                <td>{{ $registrant->id }}</td>
                <td>{{ $registrant->student->person->fullnameAlpha() }}</td>
                <td>{{ $registrant->schoolName }}</td>
                <td >{{ $registrant->instrumentations->first()->formattedDescr() }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">No registrants found</td>
            </tr>
        @endforelse
    </tbody>
</table>



