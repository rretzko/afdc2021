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
                (Route::currentRouteName() === 'eventadministrator.media.download') ||
                (Route::currentRouteName() === 'eventadministrator.media.downloads') ||
                (Route::currentRouteName() === 'eventadministrator.acknowledgedteachers') ||
                (Route::currentRouteName() === 'eventadministrator.participatingteachers') ||
                (Route::currentRouteName() === 'eventadministrator.rooms') ||
                (Route::currentRouteName() === 'eventadministrator.rooms.delete') ||
                (Route::currentRouteName() === 'eventadministrator.rooms.edit') ||
                (Route::currentRouteName() === 'eventadministrator.rooms.update') ||
                (Route::currentRouteName() === 'eventadministrator.studentcounts') ||
                (Route::currentRouteName() === 'registrationmanagers.index') ||
                (Route::currentRouteName() === 'registrationmanagers.adjudicationforms.index') ||
                (Route::currentRouteName() === 'registrationmanagers.adjudicationforms.show') ||
                (Route::currentRouteName() === 'registrationmanagers.monitorchecklists.index') ||
                (Route::currentRouteName() === 'registrationmanagers.monitorchecklists.show') ||
                (Route::currentRouteName() === 'registrationmanagers.registrantdetails.index') ||
                (Route::currentRouteName() === 'registrationmanagers.registrantdetails.show') ||
                (Route::currentRouteName() === 'registrationmanagers.registrationcards.index') ||
                (Route::currentRouteName() === 'registrationmanagers.registrationcards.show') ||
                (Route::currentRouteName() === 'registrationmanagers.timeslotassignment.index') ||
                (Route::currentRouteName() === 'registrationmanagers.timeslotassignment.update') ||
                (Route::currentRouteName() === 'registrationmanagers.timeslotstudent.index') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.index') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.index') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.send') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.store') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.test') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.update') ||
                (Route::currentRouteName() === 'eventadministrator.scoring.components') ||
                (Route::currentRouteName() === 'eventadministrator.scoring.components.delete') ||
                (Route::currentRouteName() === 'eventadministrator.scoring.components.edit') ||
                (Route::currentRouteName() === 'eventadministrator.scoring.components.update') ||
                (Route::currentRouteName() === 'eventadministrator.scoring.segments') ||
                (Route::currentRouteName() === 'eventadministrator.segments') ||
                (Route::currentRouteName() === 'eventadministrator.segments.update') ||
                (Route::currentRouteName() === 'eventadministrator.tabroom.cutoffs') ||
                (Route::currentRouteName() === 'eventadministrator.tabroom.cutoffs.update') ||
                (Route::currentRouteName() === 'eventadministrator.tabroom.publish') ||
                (Route::currentRouteName() === 'eventadministrator.tabroom.publish.update') ||
                (Route::currentRouteName() === 'eventadministrator.tabroom.reports') ||
                (Route::currentRouteName() === 'eventadministrator.tabroom.reports.participatings') ||
                (Route::currentRouteName() === 'eventadministrator.tabroom.results') ||
                (Route::currentRouteName() === 'eventadministrator.tabroom.results.show') ||
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

            @if(
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.index') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.send') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.store') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.test') ||
                (Route::currentRouteName() === 'rehearsalmanager.massmailings.concert.update')
                )
            &nbsp; - &nbsp;
            <a href="{{ route('rehearsalmanager.massmailings.index',['eventversion' => $eventversion]) }}">
                Mass Mailings
            </a>
            @endif

            @if(Route::currentRouteName() === 'registrationmanagers.timeslotstudent.index')
                &nbsp; - &nbsp;
                <a href="{{ route('registrationmanagers.timeslotassignment.index',['eventversion' => $eventversion]) }}">
                    Timeslots
                </a>
            @endif

        @endif

        @if(
                (Route::currentRouteName() === 'registrants.school.show') ||
                (Route::currentRouteName() === 'payments.index') ||
                (Route::currentRouteName() === 'registrants.school.edit')
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

            @if(
                    (Route::currentRouteName() === 'eventadministrator.tabroom.reports.participatingdirectors') ||
                    (Route::currentRouteName() === 'eventadministrator.tabroom.reports.participatingstudents')
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
                <a href="{{ route('eventadministrator.tabroom.reports',['eventversion' => $eventversion]) }}">
                    Reports
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
