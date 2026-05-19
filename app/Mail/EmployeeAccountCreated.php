<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->loginUrl = route('login');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Employee Account Credentials',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $generalSettings = \App\HelperClass::getGeneralSetting();
        return new Content(
            view: 'emails.employee_account_created',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
                'generalSettings' => $generalSettings,
                'appName' => $generalSettings->name ?? config('app.name'),
                'primaryColor' => '#974063',
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
