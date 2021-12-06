<?php

namespace App\Listeners;

use App\Events\SendLiveMassmailingEvent;
use App\Mail\MassmailingMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLiveMassmailingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * $email->email
     * @param  object  $event
     * @return void
     */
    public function handle(SendLiveMassmailingEvent $event)
    {
        foreach($event->recipients AS $person){

            foreach($person->subscriberemails AS $email){

                Mail::to($email->email)
                    ->bcc('rick@mfrholdings.com')
                    ->send(new MassmailingMail($event->massmailing, $person));

            }
        }
    }
}
