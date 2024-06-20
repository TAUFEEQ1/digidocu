<?php

use App\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionSuccessful extends Notification
{
    use Queueable;

    protected Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function via($notifiable)
    {
        return ['mail']; // Other channels like database, SMS can be added here
    }

    public function toMail($notifiable)
    {
        $receiptUrl = route('subscriptions.receipt', ['id' => $this->subscription->id]);
        $name = $notifiable->name;
        $firstName = explode(' ', $name)[0];
        return (new MailMessage)
        ->subject('Subscription Successful')
        ->greeting('Hello, ' . $firstName.',')
        ->line('Congratulations! Your subscription to the e-gazette service was successful.')
        ->line('Subscription Category:'.$this->subscription->sub_category) // Replace 'E-Gazette' with your actual subscription category
        ->action('View Subscription', route('subscriptions.index'))
        ->line('To view your subscription details, including the receipt, click the link below:')
        ->action('View Receipt', $receiptUrl)
        ->line('Thank you for using our application!');
    }
}
