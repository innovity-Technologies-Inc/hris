<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\HelperClass;
use Illuminate\Support\Facades\Lang;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $generalSettings = HelperClass::getGeneralSetting();
        $appName = $generalSettings->name ?? config('app.name');
        $primaryColor = '#974063';
        
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->view('emails.password-reset', [
                'url' => $url,
                'appName' => $appName,
                'primaryColor' => $primaryColor,
                'userName' => $notifiable->name,
                'generalSettings' => $generalSettings
            ]);
    }
}
