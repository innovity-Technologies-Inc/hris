<?php

namespace App\Notifications\Transfer;

use App\Models\Transfer\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $transfer;

    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Action Required: Transfer Approval')
                    ->line('You have been selected as an approver for a transfer request.')
                    ->line('Employee: ' . $this->transfer->employee->full_name)
                    ->action('View Request', url('/transfer/view/' . $this->transfer->id))
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'You have a pending transfer approval for ' . $this->transfer->employee->full_name,
            'url' => url('/transfer/view/' . $this->transfer->id),
            'transfer_id' => $this->transfer->id,
        ];
    }
}

