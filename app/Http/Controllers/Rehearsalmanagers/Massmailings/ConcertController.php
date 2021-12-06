<?php

namespace App\Http\Controllers\Rehearsalmanagers\Massmailings;

use App\Events\SendTestMassmailingEvent;
use App\Http\Controllers\Controller;
use App\Models\Audiencetype;
use App\Models\Eventversion;
use App\Models\Massmailing;
use App\Models\Massmailingtype;
use App\Models\Massmailingvar;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    private $massmailingtype_id;

    public function __controller()
    {
        $this->massmailingtype_id = Massmailingtype::CONCERT;
    }
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Eventversion $eventversion
     * @param string $message sent from show() and store() functions
     * @return \Illuminate\Http\Response
     */
    public function index(Eventversion $eventversion, $message=NULL)
    {
        $massmailing = Massmailing::with('massmailingvars')
            ->where('eventversion_id', $eventversion->id)
            ->where('massmailingtype_id', Massmailingtype::CONCERT)
            ->first();

        $eventensemble = $eventversion->event->eventensembles->first();

        return view('rehearsalmanagers.massmailings.concerts.index',
        [
            'eventversion' => $eventversion,
            'massmailing' => $massmailing,
            'emailbody' => $massmailing->parse(),
            'teachers' => $eventensemble->participatingTeachers($eventversion),
            'message' => $message,
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
     * Send the concert email to included list of users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) //synonym for send-email
    {
        //
    }

    /**
     * Send the concert email to the current user as a TEST email
     *
     * @param  \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function show(Eventversion $eventversion) //synonym for send-email-test
    {
        $massmailing = Massmailing::where('eventversion_id', $eventversion->id)
            ->where('massmailingtype_id', Massmailingtype::CONCERT)
            ->first();

        event(new SendTestMassmailingEvent($massmailing, auth()->user()->person));

        $message = 'Test email sent to: '.$massmailing->findVar('sender_email');

        return $this->index($eventversion, $message);
    }

    /**
     * Edit the object
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
     * @param  \App\Models\Eventversion $eventversion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Eventversion $eventversion)
    {
        $data = $request->validate([
           'concert_date' => ['required', 'string'],
           'concert_time' => ['required', 'string'],
           'arrival_time' => ['required', 'string'],
           'venue_name' => ['required', 'string'],
           'venue_shortname' => ['nullable','string'],
           'venue_address' => ['nullable', 'string'],
           'google_link' => ['nullable', 'string'],
           'postscript' => ['nullable', 'string'],
           'sender_name' => 'required', 'string',
           'sender_title' => ['required', 'string'],
           'school_address' => ['required', 'string'],
           'sender_email' => ['required', 'email'],
           'sender_phone' => ['required', 'string'],
        ]);

        $massmailing = Massmailing::firstOrCreate([
            'eventversion_id' => $eventversion->id,
            'massmailingtype_id' => Massmailingtype::CONCERT,
        ],[
            'audiencetype_id', Audiencetype::TEACHERS,
        ]);

        $massmailing_id = $massmailing->id;
        $order_by = 1;

        foreach($data AS $key => $var){

            Massmailingvar::updateOrCreate(
                [
                    'massmailing_id' => $massmailing_id,
                    'order_by'=> $order_by,
                ],
                [
                    'descr' => $key,
                    'var' => $var ?? '',
            ]);

            $order_by++;
        }

        return $this->index($eventversion);
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

    private function paragraphs()
    {
        $a = [];

        $a[] = 'Dear |*var00*|';
        $a[] = 'Thank you all in advance for giving your time on |*var01*| at |*var02*| for the |*var03*| Concert. Your
        help that day will surely contribute to a smooth, effective concert.  Here is what is expected of you when you
        arrive';
        $a[] = '<ul><li>Please be at |*var04*| no later than |*var05*| to aid with student check-in.</li>
<li>You will assist with keeping the halls quiet while getting students in and out of the auditorium.</li>
<li>While students are on stage, please stand behind the risers to aid in keeping the students quiet and any student
who is not feeling well that may come off the stage.</li>
<li>While not on stage, please stay in the rehearsal room and monitor students in the bathroom, etc.</li>
<li>Aid with check-out and clean-up at the end of the concert.</li>
    </ul>';
        $a[] = '<a href"mailto:|*var06*|">Please confirm to me that you have received this email.</a> I will send you a reminder email
a few days prior to the rehearsal.  I hope you enjoyed your Thanksgiving!';
        $a[] = '|*var07*|';
        $a[] = '|*var08*|';
        $a[] = '|*var09*|';
        $a[] = '|*var06*|';
        $a[] = '|*var10*|';
        $a[] = '|*var11*|';

        return $a;
    }


}
