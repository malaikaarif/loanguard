<?php

namespace App\Mail;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LoanApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'LoanGuard — Application #' . $this->application->id . ' ' . ucfirst($this->application->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-status-changed',
        );
    }
}