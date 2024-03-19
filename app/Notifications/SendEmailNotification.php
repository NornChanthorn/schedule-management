<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Greeting Teacher!')
                    ->line('This the nofication for alert you to fill the schedule.')
                    ->action('Click here for fill the schedule', 'http://139.59.224.162/')
                    ->line('Thank you.');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
