<?php

namespace App\Models;

use Postmark\PostmarkClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emailclient extends Model
{
    use HasFactory;

    public function packageReceived(Person $person, School $school, Eventversion $eventversion, string $emailbodyhtml, string $emailbodytext)
    {
        $client = new PostmarkClient("aebef20e-58ca-484f-adcd-11bb2ecb2318");
        $fromEmail = "rick@mfrholdings.com";
        $toEmail = "rretzko@hotmail.com";
        $subject = $eventversion->name.' packet received';
        $htmlBody = $emailbodyhtml;
        $textBody = $emailbodytext;
        $tag = "tdr package-received";
        $trackOpens = true;
        $trackLinks = "None";
        $messageStream = "outbound";

// Send an email:
        $sendResult = $client->sendEmail(
            $fromEmail,
            $toEmail,
            $subject,
            $htmlBody,
            $textBody,
            $tag,
            $trackOpens,
            NULL, // Reply To
            NULL, // CC
            NULL, // BCC
            NULL, // Header array
            NULL, // Attachment array
            $trackLinks,
            NULL, // Metadata array
            $messageStream
        );

    }
}
