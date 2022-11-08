<style>
    table{width: 100%; border-collapse: collapse; padding: .25rem;}
    td,th{border: 1px solid black;  text-align: center;}
    .page_break{page-break-before: always;}
</style>

@foreach($rooms AS $room)

    {{-- HEADERS --}}
    <table>
        <tr>
            <th style="font-weight: bold;">
                {{ $eventversion->name }}
            </th>
        </tr>
        <tr style="background-color: rgba(0,0,0,.1);">
            <th style="font-weight: bold;">
                {{ $room->descr }}
            </th>
        </tr>
    </table>

    {{-- LEGEND --}}
    <table>
        <style>
            .rating{background-color: lavender; padding: 0 1rem;}
        </style>
        <tr>
            <td class="rating">
                1-2 Superior
            </td>

            <td class="rating">
                3-4 Excellent
            </td>

            <td class="rating">
                5-6 Good
            </td>
            <td class="rating">
                7-8 Fair
            </td>
            <td class="rating">
                9 Poor
            </td>
        </tr>
    </table>

    {{-- ROWS --}}
    <style>
        table{border-collapse: collapse; width: available;margin: auto; }
        td,th{border: 1px solid black; padding: 0 .25rem; text-align: center;}
        .shaded{background-color: rgba(0,0,0,0.1);}
    </style>
    <table>
        <thead>
        <tr>
            <th colspan="2"></th>
            <th colspan="5">Scales</th>
            <th colspan="4">Solo</th>
            <th></th>
        </tr>
        <tr>
            <th colspan="2"></th>
            <th colspan="2">Low Scale</th>
            <th colspan="3">High Scale</th>
            <th colspan="4">Solo</th>
            <th style="border-bottom: 1px solid white;">Grand</th>
        </tr>
        <tr>
            <th>###</th>
            <th>Reg.Id</th>
            <th>Quality</th>
            <th>Intonation</th>
            <th>Quality</th>
            <th>Int.</th>
            <th class="shaded">Sub1</th>
            <th>Quality</th>
            <th>Int.</th>
            <th>Musicianship</th>
            <th class="shaded">Sub2</th>
            <th>Total</th>
        </tr>
        </thead>

        <tbody>
        @foreach($registrants AS $registrant)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $registrant->id }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="shaded"></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="shaded"></td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="page_break"></div>
@endforeach
