@props([
'event' => '',
'eventversion' => ''
])
<div style="display: flex; flex-direction: row; justify-content: space-between;">
<!-- {{-- {{Route::currentRouteName()}} --}} -->

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

        @if(
                (Route::currentRouteName() === 'eventadministrator.adjudicators.index') ||
                (Route::currentRouteName() === 'eventadministrator.adjudicators.delete') ||
                (Route::currentRouteName() === 'eventadministrator.adjudicators.edit') ||
                (Route::currentRouteName() === 'eventadministrator.adjudicators.update') ||
                (Route::currentRouteName() === 'eventadministrator.instrumentations') ||
                (Route::currentRouteName() === 'eventadministrator.participatingteachers') ||
                (Route::currentRouteName() === 'eventadministrator.rooms') ||
                (Route::currentRouteName() === 'eventadministrator.rooms.delete') ||
                (Route::currentRouteName() === 'eventadministrator.rooms.edit') ||
                (Route::currentRouteName() === 'eventadministrator.rooms.update') ||
                (Route::currentRouteName() === 'registrationmanagers.index') ||
                (Route::currentRouteName() === 'eventadministrator.segments') ||
                (Route::currentRouteName() === 'eventadministrator.segments.update') ||
                (Route::currentRouteName() === 'eventadministrator.tabroom.scoretracking')
            )
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

        @if(
            (Route::currentRouteName() === 'registrants.school.show') ||
            (Route::currentRouteName() === 'payments.index')
           )
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
            &nbsp; - &nbsp;
            <a href="{{ route('registrationmanagers.index',['eventversion' => $eventversion]) }}">
                Registration Manager
            </a>
        @endif

    </div>

    <div style="margin-right: 1rem;">
        <a href="{{ route('xlogout') }}">
            Log out
        </a>

        <!-- onclick="event.preventDefault(); document.getElementById('logout-form').submit();" -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

    </div>
</div>
