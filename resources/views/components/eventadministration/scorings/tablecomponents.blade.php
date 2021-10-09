<x-eventadministration.tablestyle />
<table>
    <thead>
    <tr>
        <th>###</th>
        <th>Segment</th>
        <th>Name</th>
        <th>Abbr</th>
        <th>Order</th>
        <th>Best</th>
        <th>Worst</th>
        <th>Int</th>
        <th>Tolrnc</th>
    </tr>
    </thead>
    <tbody>
    @foreach($scoringcomponents AS $scoringcomponent)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $scoringcomponent['filecontenttype']->descr }}</td>
            <td>
                @if(config('app.url'))
                    <a href="{{ route('eventadministrator.scoring.components.edit',['scoringcomponent' => $scoringcomponent->id]) }}">
                        {{ $scoringcomponent->descr }}
                    </a>
                @else
                    <a href="https://afdc-2021-l38q8.ondigitalocean.app/eventadminstrator/scoring/components/edit/{{ $scoringcomponent->id }}">
                        {{ $scoringcomponent->descr }}
                    </a>
                @endif

            </td>
            <td>{{ $scoringcomponent->abbr }}</td>
            <td>{{ $scoringcomponent->order_by }}</td>
            <td>{{ $scoringcomponent->bestscore }}</td>
            <td>{{ $scoringcomponent->worstscore }}</td>
            <td>{{ $scoringcomponent->interval }}</td>
            <td>{{ $scoringcomponent->tolerance }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
