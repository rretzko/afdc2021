@props([
'counties',
'eventversion',
'mycounties',
'toggle',
])

<div style="display:flex; justify-content: space-evenly; margin: 1rem;";>
    <div>
        @if(config('app.url') === 'http://afdc2021.test')
            <a href="{{ route('payments.index') }}">
                Payments
            </a>
        @else
            <a href="#">
                Payments
            </a>
        @endif
    </div>


    @if($eventversion->eventversionconfig->virtualaudition)
        <div>
            Uploads
        </div>
    @endif


</div>
