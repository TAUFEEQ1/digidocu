<?php

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionFailed extends Notification
{
    use Queueable;

    protected $error;

    public function __construct($error)
    {
        $this->error = $error;
    }

    public function via($notifiable)
    {
        return ['mail']; // Other channels like database, SMS can be added here
    }

    public function toMail($notifiable)
    {
        $name = $notifiable->name;
        $firstName = explode(' ', $name)[0];
        return (new MailMessage)
                    ->subject('Subscription Failed')
                    ->greeting('Hello! '.$firstName)
                    ->line('We regret to inform you that your subscription attempt failed.')
                    ->line('Reason: ' . $this->error)
                    ->action('Retry Subscription', route('subscriptions.index'))
                    ->line('Please try again or contact support for assistance.')
                    ->line('Thank you for using our application.');
    }
}
