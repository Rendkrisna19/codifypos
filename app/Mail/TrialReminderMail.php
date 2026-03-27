<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;
    public $owner;
    public $daysLeft;

    public function __construct($tenant, $owner, $daysLeft)
    {
        $this->tenant = $tenant;
        $this->owner = $owner;
        $this->daysLeft = $daysLeft;
    }

    public function envelope(): Envelope
    {
        $subject = $this->daysLeft < 0 
            ? 'PERHATIAN: Masa Aktif CodifyPOS Berakhir' 
            : 'Peringatan Masa Trial CodifyPOS';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trial-reminder',
        );
    }
}