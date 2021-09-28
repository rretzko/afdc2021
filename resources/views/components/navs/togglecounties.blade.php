@props([
'counties',
'mycounties',
'toggle',
])
<div style="display:flex; justify-content: space-evenly; margin: 1rem;";>
    <div style="width: 100%; text-align: center; border-top: 1px solid lightgrey; border-left: 1px solid lightgrey; margin-right: .25rem;
    {{ ($toggle === 'my') ? 'box-shadow: 5px 5px 5px darkgrey; background-color: white' : 'background-color: lightgrey' }};">
        <a href="{{ route('registrationmanager.show',['counties' => 'my']) }}"
           style="color: {{ ($toggle === 'all') ? 'blue' : 'black' }};"
        >
            My Counties ({{ count($mycounties) }})
        </a>
    </div>

    <div style="width: 100%; text-align: center; border-top: 1px solid lightgrey; border-left: 1px solid lightgrey; margin-right: .25rem;
    {{ ($toggle === 'all') ? 'box-shadow: 5px 5px 5px darkgrey; background-color: white' : 'background-color: lightgrey' }};">
        <a href="{{ route('registrationmanager.show',['counties' => 'all']) }}"
           style="color: {{ ($toggle === 'all') ? 'blue' : 'black' }};"
        >
            All Counties ({{ count($counties) }})
        </a>
    </div>

</div>
