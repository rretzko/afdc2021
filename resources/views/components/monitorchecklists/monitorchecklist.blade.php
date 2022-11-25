@props([
    'eventversion',
    'registrants',
    'room',
])
<div style="border: 1px solid black; padding: .25rem; margin: .25rem;">

    <div style="text-align: center; font-weight: bold;">
        {{ $eventversion->name }}
    </div>

    <div style="text-align: center; font-weight: bold; background-color: rgba(0,0,0,.1); margin-bottom: .5rem; border-bottom: 1px solid black; border-top: 1px solid black;">
        {{ $room->descr }}
    </div>

    <div style="">
        <style>
            table{border-collapse: collapse; width: available;margin: auto; }
            td,th{border: 1px solid black; padding: 0 .25rem; text-align: center;}
        </style>
        <table>
            <thead>
            <tr>
                <th>###</th>
                <th>Timeslot</th>
                <th>Reg.Id</th>
                <th style="width: 20rem;">Notes to Tab Room</th>
            </tr>
            </thead>

            <tbody>
            @foreach($registrants AS $registrant)
                <tr>
                    <td style="color: darkgray;">{{ $loop->iteration }}</td>
                    <td>{{ $registrant->timeslot }}</td>
                    <td>{{ $registrant->id }}</td>
                    <td></td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

</div>
