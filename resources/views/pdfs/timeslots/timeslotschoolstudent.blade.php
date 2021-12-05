<style>
    table{border-collapse: collapse; padding: .25rem;}
    td,th{border: 1px solid black; padding: 0 .25rem;}
    .page_break{page-break-before: always;}
</style>
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
            <td>{{ $registrant->schoolShortname }}</td>
            <td >{{ $registrant->instrumentations->first()->formattedDescr() }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2">No registrants found</td>
        </tr>
    @endforelse
    </tbody>
</table>
