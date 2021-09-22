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

    /** DASHBOARD */
    Route::post('/dashboard/event', 'EventController@store')->name('event.store');
    Route::get('/dashboard/events', 'EventController@store')->name('dashboard.events.index');
    Route::get('/dashboard/versions', 'EventversionController@index')->name('dashboard.eventversions.index');
    Route::get('/dashboard/invitations', 'InvitationsController@index')->name('dashboard.invitations.index');
    Route::get('/dashboard/members', [App\Http\Controllers\Members\MembersController::class, 'index'])->name('members');
    Route::get('/dashboard/organizations', [App\Http\Controllers\OrganizationsController::class, 'index'])->name('organizations');

    /** REGISTRATION MANAGERS */
    Route::get('/registrationmanager', [App\Http\Controllers\Registrationmanagers\RegistrationmanagerController::class, 'index'])->name('registrationmanagers.index');
    Route::get('/registrationmanager/{counties}', [App\Http\Controllers\Registrationmanagers\RegistrationmanagerController::class, 'show'])->name('registrationmanager.show');

});
