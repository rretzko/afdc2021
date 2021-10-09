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
            <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/payments">
                Payments
            </a>
        @endif
    </div>

    <div>
        @if(config('app.url') === 'http://afdc2021.test')
            <a href="{{ route('registrationmanager.participatingteachers.index') }}">
                Participating Teachers
            </a>
        @else
            <a href="https://afdc-2021-l38q8.ondigitalocean.app/registrationmanager/participatingteachers">
                Participating Teachers
            </a>
        @endif
    </div>


    @if($eventversion->eventversionconfig->virtualaudition)
        <div>
            @if(config('app.url') === 'http://afdc2021.test')
                Registrants
            @else
                Registrants
            @endif
        </div>
    @endif


</div>
