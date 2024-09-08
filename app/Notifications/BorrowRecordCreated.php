<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BorrowRecordCreated extends Notification
{
    use Queueable;

    protected $bookId;

    public function __construct($bookId)
    {
        $this->bookId = $bookId;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A book has been borrowed.')
                    ->action('View Book', url('/books/' . $this->bookId))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'book_id' => $this->bookId,
        ];
    }
}
