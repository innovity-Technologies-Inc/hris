<?php

namespace App\Mail\Employee;

use App\Models\Employee\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfileActiveMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;

    /**
     * Create a new message instance.
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Profile Activated Successfully',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $generalSettings = \App\HelperClass::getGeneralSetting();
        return new Content(
            view: 'emails.profile_active',
            with: [
                'employee' => $this->employee,
                'generalSettings' => $generalSettings,
                'appName' => $generalSettings->name ?? config('app.name'),
                'primaryColor' => '#974063',
            ]
        );
    }
}

