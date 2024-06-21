<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdvertSubmittedToRegistrar extends Notification
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
                    ->subject('New Advert Submission')
                    ->greeting('Hello, ' . $notifiable->name . '!')
                    ->line('A new advert has been submitted and requires your review.')
                    ->action('View Advert', $url)
                    ->line('Please review the advert at your earliest convenience.')
                    ->line('Thank you!');
    }
}
