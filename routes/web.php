<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/* DEMO */
Route::get('/demo', 'Demo\DemoHomeController@index')->name('demo');

/* LOG IN */
Route::get('/login', [App\Http\Controllers\Afdcauth\LoginController::class, 'create'])->name('login');
Route::post('/login/update', [App\Http\Controllers\Afdcauth\LoginController::class, 'update'])->name('login.update');
Route::post('/logout', [App\Http\Controllers\Afdcauth\LoginController::class, 'destroy'])->name('logout');

/* AUTHORIZED USERS */
Route::group(['middleware' => 'auth'],function(){

    /** LOGOUT */
    Route::get('/logout', function(){
            auth()->logout();
            return view('welcome');
        })->name('xlogout');

    /** HOME PAGE */
    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    /** DASHBOARD */
    Route::post('/dashboard/event', 'EventController@store')->name('event.store');
    Route::get('/dashboard/events', 'EventController@store')->name('dashboard.events.index');
    Route::get('/dashboard/versions', 'EventversionController@index')->name('dashboard.eventversions.index');
    Route::get('/dashboard/invitations', 'InvitationsController@index')->name('dashboard.invitations.index');
    Route::get('/dashboard/members', [App\Http\Controllers\Members\MembersController::class, 'index'])->name('members');
    Route::get('/dashboard/organizations', [App\Http\Controllers\OrganizationsController::class, 'index'])->name('organizations');

    /** EVENT ADMINISTRATION */
    Route::get('/eventadministration/{event}',[App\Http\Controllers\Eventadministration\EventadministrationController::class, 'index'])
        ->name('eventadministration.index');
    Route::get('/eventadministration/eventversion/{eventversion}',[App\Http\Controllers\Eventadministration\EventversionController::class, 'index'])
        ->name('eventadministration.eventversion.index');

    /** EVENT ADMINISTRATOR */
    Route::get('/eventadministrator', [App\Http\Controllers\Eventadministration\EventadministratorController::class, 'index'])
        ->name('eventadministrator.index');

    Route::get('/eventadministrator/adjudicators/{eventversion}', [App\Http\Controllers\Eventadministration\AdjudicatorController::class, 'index'])
        ->name('eventadministrator.adjudicators.index');
    Route::post('/eventadministrator/adjudicators/update', [App\Http\Controllers\Eventadministration\AdjudicatorController::class, 'update'])
        ->name('eventadministrator.adjudicators.update');
    Route::get('/eventadministrator/adjudicators/edit/{id}', [App\Http\Controllers\Eventadministration\AdjudicatorController::class, 'edit'])
        ->name('eventadministrator.adjudicators.edit');
    Route::get('/eventadministrator/adjudicators/delete/{id}', [App\Http\Controllers\Eventadministration\AdjudicatorController::class, 'destroy'])
        ->name('eventadministrator.adjudicators.delete');

    Route::get('/eventadministrator/tabroom/cutoffs/{eventversion}', [App\Http\Controllers\Eventadministration\CutoffController::class, 'index'])
        ->name('eventadministrator.tabroom.cutoffs');
    Route::get('/eventadministrator/tabroom/cutoffs/{instrumentation_id}/{cutoff}', [App\Http\Controllers\Eventadministration\CutoffController::class, 'update'])
        ->name('eventadministrator.tabroom.cutoffs.update');
    Route::get('/eventadministrator/tabroom/lock/{eventensemble}', [App\Http\Controllers\Eventadministration\CutofflockController::class, 'update'])
        ->name('eventadministrator.tabroom.cutoffs.lock');

    Route::get('/eventadministrator/tabroom/publish', [App\Http\Controllers\Eventadministration\PublishresultsController::class, 'index'])
        ->name('eventadministrator.tabroom.publish');
    Route::get('/eventadministrator/tabroom/publish/update', [App\Http\Controllers\Eventadministration\PublishresultsController::class, 'update'])
        ->name('eventadministrator.tabroom.publish.update');

    Route::get('/eventadministrator/tabroom/report/{eventversion}', [App\Http\Controllers\Eventadministration\ReportsController::class, 'index'])
        ->name('eventadministrator.tabroom.reports');
    Route::get('/eventadministrator/tabroom/reports/auditionresults', [App\Http\Controllers\Eventadministration\ReportsAuditionresultsController::class, 'index'])
        ->name('eventadministrator.tabroom.reports.auditionresults');
    Route::get('/eventadministrator/tabroom/reports/participatingdirectors', [App\Http\Controllers\Eventadministration\ReportsParticipatingdirectorsController::class, 'index'])
        ->name('eventadministrator.tabroom.reports.participatingdirectors');
    Route::get('/eventadministrator/tabroom/reports/participatingstudents', [App\Http\Controllers\Eventadministration\ReportsParticipatingstudentsController::class, 'index'])
        ->name('eventadministrator.tabroom.reports.participatingstudents');
    Route::get('/eventadministrator/tabroom/reports/participatingstudents', [App\Http\Controllers\Eventadministration\ReportsParticipatingstudentsController::class, 'index'])
        ->name('eventadministrator.tabroom.reports.participatings');

    Route::get('/eventadministrator/tabroom/results/{eventversion}', [App\Http\Controllers\Eventadministration\AuditionresultsController::class, 'index'])
        ->name('eventadministrator.tabroom.results');
    Route::get('/eventadministrator/tabroom/results/show/{eventversion}/{instrumentation}', [App\Http\Controllers\Eventadministration\AuditionresultsController::class, 'show'])
        ->name('eventadministrator.tabroom.results.show');

    //Route::get('/eventadministrator/instrumentations/{eventversion}', [App\Http\Controllers\Eventadministration\AuditioninstrumentationController::class, 'index'])
     //   ->name('eventadministrator.instrumentations');

    Route::get('/eventadministrator/instrumentations/{eventversion}', [App\Http\Controllers\Eventadministration\AuditioninstrumentationController::class, 'index'])
        ->name('eventadministrator.instrumentations');
    Route::post('/eventadministrator/instrumentations/update', [App\Http\Controllers\Eventadministration\AuditioninstrumentationController::class, 'update'])
        ->name('eventadministrator.instrumentations.update');

    Route::get('/eventadministrator/rooms/{eventversion}', [App\Http\Controllers\Eventadministration\AuditionroomController::class, 'index'])
        ->name('eventadministrator.rooms');
    Route::get('/eventadministrator/rooms/delete/{room}', [App\Http\Controllers\Eventadministration\AuditionroomController::class, 'destroy'])
        ->name('eventadministrator.rooms.delete');
    Route::get('/eventadministrator/rooms/edit/{room}', [App\Http\Controllers\Eventadministration\AuditionroomController::class, 'edit'])
        ->name('eventadministrator.rooms.edit');
    Route::post('/eventadministrator/rooms/update/{eventversion}/{room?}', [App\Http\Controllers\Eventadministration\AuditionroomController::class, 'update'])
        ->name('eventadministrator.rooms.update');

    Route::get('/eventadministrator/scoring/segments/{eventversion}', [App\Http\Controllers\Eventadministration\AuditionscoringsegmentController::class, 'index'])
        ->name('eventadministrator.scoring.segments');
    Route::get('/eventadministrator/scoring/components/{eventversion}', [App\Http\Controllers\Eventadministration\AuditionscoringcomponentController::class, 'index'])
        ->name('eventadministrator.scoring.components');
    Route::get('/eventadministrator/scoring/components/delete/{scoringcomponent}', [App\Http\Controllers\Eventadministration\AuditionscoringcomponentController::class, 'destroy'])
        ->name('eventadministrator.scoring.components.delete');
    Route::get('/eventadministrator/scoring/components/edit/{scoringcomponent}', [App\Http\Controllers\Eventadministration\AuditionscoringcomponentController::class, 'edit'])
        ->name('eventadministrator.scoring.components.edit');
    Route::post('/eventadministrator/scoring/components/update/{eventversion}/{scoringcomponent}', [App\Http\Controllers\Eventadministration\AuditionscoringcomponentController::class, 'update'])
        ->name('eventadministrator.scoring.components.update');

    Route::get('/eventadministrator/segments/{eventversion}', [App\Http\Controllers\Eventadministration\AuditionsegmentController::class, 'index'])
        ->name('eventadministrator.segments');
    Route::post('/eventadministrator/segments/update/{eventversion}', [App\Http\Controllers\Eventadministration\AuditionsegmentController::class, 'update'])
        ->name('eventadministrator.segments.update');

    /** REGISTRATION MANAGERS */
    Route::get('/registrationmanager/{eventversion}', [App\Http\Controllers\Registrationmanagers\RegistrationmanagerController::class, 'index'])
        ->name('registrationmanagers.index');
    Route::get('/registrationmanager/payments/{eventversion}', [App\Http\Controllers\Registrationmanagers\PaymentController::class, 'index'])
        ->name('payments.index');
    Route::get('/registrationmanager/{counties}', [App\Http\Controllers\Registrationmanagers\RegistrationmanagerController::class, 'show'])->name('registrationmanager.show');
    Route::get('/registrationmanager/registrants/county/{county}', [App\Http\Controllers\Registrationmanagers\RegistrantcountyController::class, 'show'])
        ->name('registrants.county.show');
    Route::get('/registrationmanager/registrants/school/{eventversion}/{school}', [App\Http\Controllers\Registrationmanagers\RegistrantschoolController::class, 'show'])
        ->name('registrants.school.show');
    Route::get('/registrationmanager/registrants/school/{eventversion}/{school}/{registrant}', [App\Http\Controllers\Registrationmanagers\RegistrantschoolController::class, 'edit'])
        ->name('registrants.school.edit');
    Route::post('/registrationmanager/registrant/update/{registrant}', [App\Http\Controllers\Registrationmanagers\RegistrationmanagerController::class, 'update'])
        ->name('registrationmanager.registrant.update');

    Route::get('/eventadministrator/participatingteachers/{eventversion?}', [App\Http\Controllers\Eventadministration\ParticipatingteachersController::class, 'index'])
        ->name('eventadministrator.participatingteachers');

    Route::get('/eventadministrator/tabroom/scoretracking/{eventversion}', [App\Http\Controllers\Eventadministration\ScoretrackingController::class, 'index'])
        ->name('eventadministrator.tabroom.scoretracking');

});
