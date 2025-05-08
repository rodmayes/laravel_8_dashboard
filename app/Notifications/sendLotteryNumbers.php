<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class sendLotteryNumbers extends Notification implements ShouldQueue
{
    use Queueable;

    private $lotteryNumbers;

    /**
     * Create a new notification instance.
     */
    public function __construct($magik_numbers)
    {
        $this->magik_numbers = $magik_numbers;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('These are the lottery numbers that have been sent to you.')
                    ->line($this->lotteryNumbers)
                    ->line('Thank you for using our application and good luck!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
