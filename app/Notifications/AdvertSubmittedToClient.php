<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdvertSubmittedToClient extends Notification
{
    use Queueable;

    protected $advert;

    public function __construct($advert)
    {
        $this->advert = $advert;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('adverts.show', ['id' => $this->advert->id]);
        return (new MailMessage)
                    ->subject('Your Advert Has Been Submitted')
                    ->greeting('Hello, ' . $notifiable->name . '!')
                    ->line('Thank you for submitting your advert.')
                    ->action('View Your Advert', $url)
                    ->line('We will review your advert and get back to you shortly.')
                    ->line('Thank you for using our application!');
    }
}
