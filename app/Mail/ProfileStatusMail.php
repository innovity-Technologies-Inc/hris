<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfileStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct(Employee $employee, string $status)
    {
        $this->employee = $employee;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusText = ucfirst($this->status);
        return new Envelope(
            subject: "Profile Status Updated: {$statusText}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $generalSettings = \App\HelperClass::getGeneralSetting();
        return new Content(
            view: 'emails.profile_status',
            with: [
                'employee' => $this->employee,
                'status' => $this->status,
                'generalSettings' => $generalSettings,
                'appName' => $generalSettings->name ?? config('app.name'),
                'primaryColor' => '#974063',
            ]
        );
    }
}
