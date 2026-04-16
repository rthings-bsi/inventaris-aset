<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\AssetLoan;

class AssetLoanNotification extends Notification
{
    use Queueable;

    public $loan;
    public $type;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(AssetLoan $loan, $type, $message)
    {
        $this->loan = $loan;
        $this->type = "loan_" . $type;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'loan_id' => $this->loan->id,
            'asset_id' => $this->loan->asset_id,
            'asset_name' => $this->loan->asset->asset_name,
            'message' => $this->message,
            'type' => $this->type,
            'url' => route('loans.index')
        ];
    }
}
