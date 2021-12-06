<?php

namespace App\Mail;

use App\Models\Massmailing;
use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MassmailingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $massmailing;
    public $person;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Massmailing $massmailing, Person $person)
    {
        $this->massmailing = $massmailing;
        $this->person = $person;
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
                'massmailing' => $this->massmailing,
                'person' => $this->person,
            ]);
    }
}
