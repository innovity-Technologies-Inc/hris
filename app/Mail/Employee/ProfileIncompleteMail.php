<?php

namespace App\Mail\Employee;

use App\Models\Employee\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfileIncompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $cause;

    /**
     * Create a new message instance.
     */
    public function __construct(Employee $employee, $cause)
    {
        $this->employee = $employee;
        $this->cause = $cause;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Profile Review: Incomplete Information',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $generalSettings = \App\HelperClass::getGeneralSetting();
        return new Content(
            view: 'emails.profile_incomplete',
            with: [
                'employee' => $this->employee,
                'cause' => $this->cause,
                'generalSettings' => $generalSettings,
                'appName' => $generalSettings->name ?? config('app.name'),
                'primaryColor' => '#974063',
            ]
        );
    }
}

