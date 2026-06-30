<?php

namespace App\Notifications\Approval;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalActionRequiredNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public \Innovity\ApprovalEngine\Models\ApprovalStepRequest $stepRequest)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $moduleName = $this->stepRequest->approvalRequest->workflow->module_name ?? '';
        $module = ucfirst($moduleName ?: 'Item');
        
        $approvable = $this->stepRequest->approvalRequest->approvable;
        $url = \Illuminate\Support\Facades\Route::has($moduleName . '.show') && $approvable
                ? route($moduleName . '.show', $approvable->id) 
                : url('/' . $moduleName);

        return (new MailMessage)
            ->subject("Action Required: $module Approval Request")
            ->greeting("Hello " . $notifiable->name . ",")
            ->line("You have a new $module approval request pending your action.")
            ->action('View Request', $url)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $moduleName = $this->stepRequest->approvalRequest->workflow->module_name ?? '';
        $module = ucfirst($moduleName ?: 'Item');
        
        $approvable = $this->stepRequest->approvalRequest->approvable;
        $url = \Illuminate\Support\Facades\Route::has($moduleName . '.show') && $approvable
                ? route($moduleName . '.show', $approvable->id, false) 
                : '/' . $moduleName;
        
        return [
            'title' => 'Approval Action Required',
            'message' => "You have a new $module approval request pending your action.",
            'url' => $url,
            'type' => 'approval_request'
        ];
    }
}
