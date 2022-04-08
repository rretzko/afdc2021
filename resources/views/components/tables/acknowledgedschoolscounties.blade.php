@props([
'applicants' => 0,
'counties' => false,
'eventversion' => false,
'mycounties' => false,
'myschools' => false,
'registrationactivity' => false,
'schools' => false,
'toggle',
])
<div style="display: none;">{{ set_time_limit(90) }}</div>
<style>
    table{ border-collapse: collapse; margin-bottom: 1rem;}
    td,th{border: 1px solid black; padding:0 .25rem; text-align: center;}
</style>

<table>
    <thead>
    <tr>
        <th>###</th>
        <th>School Name</th>
        <th>Applicants ({{ $applicants }})</th>
    </tr>
    </thead>
    <tbody>
    @forelse($schools->sortBy('name') AS $school)
        <tr style="@if(! ($loop->iteration % 5)) background-color: rgba(0,0,0,0.1); @endif">
            <td class="text-right">{{ $loop->iteration }}</td>
            <td class="text-left">{{ $school->shortname.' ('.$school->id.')' }}</td>
            <td>{{ $school->applicantsCount($eventversion) }}</td>
        </tr>
    @empty
        <tr><td colspan="2">No Schools Found</td></tr>
    @endforelse
    </tbody>
</table>



