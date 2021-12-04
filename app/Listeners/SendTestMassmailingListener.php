<?php

namespace App\Listeners;

use App\Events\SendTestMassmailingEvent;
use App\Mail\MassmailingMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTestMassmailingListener
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
     *
     * @param  \App\Events\SendTestMassmailingEvent  $event
     * @return void
     */
    public function handle(SendTestMassmailingEvent $event)
    {

        Mail::to($event->emailto)
            //->bcc($bcc)
            ->send(new MassmailingMail($event->massmailing));
    }
}
