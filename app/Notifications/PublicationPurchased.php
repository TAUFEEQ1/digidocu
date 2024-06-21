<?php
namespace App\Notifications;

use App\PublicationBuyer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PublicationPurchased extends Notification
{
    use Queueable;

    protected PublicationBuyer $publication_buyer;

    public function __construct(PublicationBuyer $publication_buyer)
    {
        $this->publication_buyer = $publication_buyer;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Publication Purchase Successful')
                    ->greeting('Hello, ' . $notifiable->name . '!')
                    ->line('Thank you for purchasing the publication: ' . $this->publication_buyer->publication->pub_title)
                    ->line('Purchase Date: ' . $this->publication_buyer->paid_at)
                    ->line('Total Amount: ' . $this->publication_buyer->publication->pub_fees)
                    ->action('View Your Purchase', route("publications.view",["id"=>$this->publication_buyer->publication->id]))
                    ->line('You can view your receipt at the following link:')
                    ->action('View Receipt', route('publications.receipt', ['id' => $this->publication_buyer->publication->id]))
                    ->line('Thank you for using our application!');
    }
}
