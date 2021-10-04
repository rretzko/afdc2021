<div style="margin: auto;">
    <style>
        table{border-collapse: collapse; border: 1px solid black; }
        td,th{border: 1px solid black; padding:0 .25rem;}
    </style>
    <table id="checkregister">
        <thead>
            <tr>
                <th>###</th>
                <th>School</th>
                <th>Teacher</th>
                <th>Amount</th>
                <th>Comments</th>
                <th>Updated by</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments AS $payment)
                <tr style="background-color: @if($loop->odd) white @else rgba(0,255,0,.1) @endif">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $payment['school']->name }}</td>
                    <td>{{ $payment['person']->fullnameAlpha() }}</td>
                    <td style="text-align: right;">{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->comments }}</td>
                    <td>{{ $payment->updatedByFullnameAlpha() }}</td>
                    <td>{{ $payment->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
