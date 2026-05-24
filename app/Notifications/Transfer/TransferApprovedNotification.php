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
        return ['database']; // Keeping it simple for now, can add mail if needed
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

