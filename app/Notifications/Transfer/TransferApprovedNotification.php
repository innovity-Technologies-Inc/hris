<?php

namespace App\Notifications\Transfer;

use App\Models\Transfer\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferApprovedNotification extends Notification
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
                    ->subject('Transfer Request Approved')
                    ->line('Your transfer request for ' . $this->transfer->employee->full_name . ' has been approved by an authority.')
                    ->action('View Request', url('/transfer/view/' . $this->transfer->id))
                    ->line('Final completion will be processed by HR.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Transfer request for ' . $this->transfer->employee->full_name . ' has been approved by an authority.',
            'url' => url('/transfer/view/' . $this->transfer->id),
            'transfer_id' => $this->transfer->id,
        ];
    }
}

