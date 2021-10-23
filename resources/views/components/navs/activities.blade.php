@props([
'counties',
'eventversion',
'mycounties',
'toggle',
])

<div style="display:flex; justify-content: space-evenly; margin: 1rem;";>
    <div>
        @if(config('app.url') === 'http://afdc2021.test')
            <a href="{{ route('payments.index',['eventversion' => $eventversion]) }}">
                Payments
            </a>
        @else
            <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/payments/{{ $eventversion->id }}">
                Payments
            </a>
        @endif
    </div>
</div>
