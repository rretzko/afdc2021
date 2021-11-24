@extends('layouts.app')

<style>
    section{width: available; border-bottom: 1px solid darkblue; padding-bottom: .5rem; margin-bottom: 1rem;}
</style>
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <x-logout  :event="$eventversion->event" :eventversion="$eventversion"/>

                <div class="card">

                    <div class="card-header col-12 d-flex">
                        <div class="text-left col-5">
                            {{ __('Rehearsal Manager: Mass Mailings: Concert: ').$eventversion->name }}
                        </div>
                        <div class="text-right col-7">
                            {{  __('Welcome back, ') }}{{ auth()->user()->person->first }}
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: row; justify-content: space-between; padding: 1rem .5rem;">
                        <div style="display: flex; flex-direction: column; width: 66%;">
                            <section id="buttons" >
                                <div style="display: flex; flex-direction: row; justify-content: space-around;">
                                    <div>
                                        <a href="">
                                            <button style="border-radius: .5rem; background-color: blanchedalmond;">
                                                Send Test Email
                                            </button>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="">
                                            <button style="border-radius: .5rem; background-color: darkseagreen; color: white;">
                                                Send LIVE Email
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </section>
                            <section id="variables">
                                <div>Concert Date</div>
                                <div>Concert Time</div>
                                <div>Arrival Time</div>
                                <div>Venue name</div>
                                <div>Venue short name</div>
                                <div>Venue address</div>
                                <div>Venue google address link</div>
                                <div>Postscript</div>
                                <div>Sender name</div>
                                <div>Sender title</div>
                                <div>Sender School address block</div>
                                <div>Sender email address</div>
                                <div>Sender contact number</div>
                            </section>
                            <section id="display" style="background-color: aliceblue; padding: .5rem;">
                                <p>
                                    Dear [firstname]
                                </p>
                                <p>
                                    Thank you all in advance for giving your time on [concert date] at [venue name] for the [concert time] Concert. Your help that day will surely contribute to a smooth, effective concert.  Here is what is expected of you when you arrive:
                                </p>
                                <ul>
                                    <li>Please be at [concert short name] no later than [arrival time] to aid with student check-in</li>
                                    <li>You will assist with keeping the halls quiet while getting students in and out of the auditorium.</li>
                                    <li>While students are on stage, please stand behind the risers to aid in keeping the students quiet and any student who is not feeling that may come off the stage.</li>
                                    <li>While not on stage, please stay in the rehearsal room and monitor students in the bathroom, etc.</li>
                                    <li>Aid with check-out and clean up at the end of the concert.</li>
                                </ul>
                                <p>
                                    <b>Please [sender email address]confirm to me that you have received this email.</b>  I will send you a reminder email a few days prior to the rehearsal. I hope you enjoyed your Thanksgiving!
                                </p>
                                <p>
                                    Thanks you!
                                </p>
                                <p>
                                    [Sender name]<br />
                                    [Sender title]<br />
                                    [Sender School address block]<br />
                                    [Sender email address]<br />
                                    [Sender contact number]<br />
                                </p>
                                <p>
                                    [Postscript]
                                </p>
                            </section>
                        </div>
                        <section id="participants" style="border: 1px solid darkblue;padding: .5rem .25rem;">Participant checklist</section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
