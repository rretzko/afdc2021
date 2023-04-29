<?php

namespace App\Http\Controllers\Eventadministration;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Eventversion;
use App\Models\Userconfig;
use App\Models\Utility\RegistrationActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScoretrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion)
    { $start = Carbon::now();
Log::info('*** start: ' . $start);
        $registrationactivity = new RegistrationActivity(['eventversion' => $eventversion, 'counties' => []]);

        set_time_limit(360);

        $registrants = $registrationactivity->registeredTotalByInstrumentation();

$end = Carbon::now();
Log::info('registrants duration: '.$end->diffInSeconds($start));

        return view('eventadministration.scoretrackings.index',
        [
           'eventversion' => $eventversion,
           'registrants' => $registrants,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
