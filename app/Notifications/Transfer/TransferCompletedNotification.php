<?php

namespace App\Notifications\Transfer;

use App\Models\Transfer\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferCompletedNotification extends Notification implements ShouldQueue
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
                    ->subject('Transfer Request Completed')
                    ->line('Your transfer request has been fully approved and completed.')
                    ->line('Your office information has been updated accordingly.')
                    ->action('View Details', url('/transfer/view/' . $this->transfer->id))
                    ->line('Thank you!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your transfer request has been completed and office info updated.',
            'url' => url('/transfer/view/' . $this->transfer->id),
            'transfer_id' => $this->transfer->id,
        ];
    }
}

