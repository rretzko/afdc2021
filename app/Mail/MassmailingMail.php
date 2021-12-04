<?php

namespace App\Mail;

use App\Models\Massmailing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MassmailingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $massmailing;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Massmailing $massmailing)
    {
        $this->massmailing = $massmailing;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.massmailings.concert',
            [

            ]);
    }
}
