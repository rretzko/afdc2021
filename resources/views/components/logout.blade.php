@props([
'event' => '',
'eventversion' => ''
])
<div style="display: flex; flex-direction: row; justify-content: space-between;">

    <div style="display: flex; flex-direction: row; justify-content: space-between;">
        @if(Route::currentRouteName() === 'eventadministration.index')
            <a href="{{ route('home') }}">
                Home
            </a>
        @endif

        @if(Route::currentRouteName() === 'eventadministration.eventversion.index')
            <a href="{{ route('home') }}">
                Home
            </a>
             &nbsp; - &nbsp;
            <a href="{{ route('eventadministration.index',['event' => $event]) }}">
                Eventversions
            </a>
        @endif

        @if((Route::currentRouteName() === 'eventadministrator.participatingteachers') ||
            (Route::currentRouteName() === 'registrationmanagers.index'))
            <a href="{{ route('home') }}">
                Home
            </a>
            &nbsp; - &nbsp;
            <a href="{{ route('eventadministration.index',['event' => $event]) }}">
                Eventversions
            </a>

            &nbsp; - &nbsp;
            <a href="{{ route('eventadministration.eventversion.index',['eventversion' => $eventversion]) }}">
                Eventversion
            </a>
        @endif


        <!-- {{--
        @if(Route::currentRouteName() === 'eventadministration.index')
            <a href="{{ route('eventadministration.index', ['event' => $event]) }}">
                Events
            </a>
        @endif
        --}} -->

        <!-- {{-- @if(
            (auth()->id() === 21) ||
            (auth()->id() === 28) ||
            (auth()->id() === 38) ||
            (auth()->id() === 45) ||
            (auth()->id() === 82)
        )
            @if(request()->is('eventadministrator') || request()->is('eventadministrator/*'))
                <a href="{{ route('registrationmanagers.index') }}">
                    Registration manager
                </a>
                <div style="margin:0 0.5rem;">|</div>
            @endif
            <a href="{{ route('eventadministrator.index') }}">
                Event administrator
            </a>
        @endif
        --}} -->
    </div>

    <div style="margin-right: 1rem;">
        <a href="{{ route('xlogout') }}">
            Log out
        </a>

        <!-- onclick="event.preventDefault(); document.getElementById('logout-form').submit();" -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

    </div>
</div>
