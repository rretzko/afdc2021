<style>
    table{width: 100%; border-collapse: collapse; padding: .25rem;}
    td,th{border: 1px solid black;  text-align: center;}
    .page_break{page-break-before: always;}
</style>

{{-- @foreach($rooms AS $room) --}}

    {{-- HEADERS --}}
    <table>
        <tr>
            <th style="font-weight: bold;">
                {{ $eventversion->name }}
            </th>
        </tr>
        <tr style="background-color: rgba(0,0,0,.1);">
            <th style="font-weight: bold;  font-size: 2rem;">
                MONITOR CHECKLIST: {{ $room->descr }}
            </th>
        </tr>
        <tr>
            <td style="font-size: 1.15rem;">
                <div style="display:flex; flex-direction: row; justify-content: space-around;">
                    @forelse($room['adjudicators'] AS $adjudicator)
                        <span style="padding: 0 1rem; @if($loop->last) border-right: 1px solid darkgrey; @endif border-left: 1px solid darkgrey;">{{ $adjudicator->adjudicatorName }}</span>
                    @empty
                        <div>No Adjudicator found</div>
                    @endforelse
                </div>
            </td>
        </tr>
    </table>

    {{-- ROWS --}}
    <table>
        <thead>
        <tr>
            <th style="width: 2rem;">###</th>
            <th style="width: 4rem;">Timeslot</th>
            <th style="width: 4rem;">Reg.Id</th>
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

    <div class="page_break"></div>
{{-- @endforeach --}}
