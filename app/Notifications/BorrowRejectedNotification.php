<?php
namespace App\Notifications;

use App\Models\BorrowRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BorrowRejectedNotification extends Notification
{
    use Queueable;

    protected $borrowRecord;
    protected $reason;

    public function __construct(BorrowRecord $borrowRecord, $reason = null)
    {
        $this->borrowRecord = $borrowRecord;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];  // You can add other channels like 'database' if needed
    }

    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('Borrow Request Rejected')
            ->line('Your request to borrow the book "' . $this->borrowRecord->book->title . '" has been rejected.');

        if ($this->reason) {
            $mailMessage->line('Reason: ' . $this->reason);
        }

        return $mailMessage;
    }
}
