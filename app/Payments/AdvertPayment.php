<?php
namespace App\Payments;
use App\Payments\BasePayment;
use App\Advert;
use App\Document;
use App\Notifications\AdvertSubmittedToClient;
use App\Notifications\AdvertSubmittedToRegistrar;
use App\Subscription;
use App\User;

class AdvertPayment extends BasePayment{

    private Document|Advert $document;

    public function setDocument(Document|Advert $document)
    {
        $this->document = $document;
    }
    public function setStatus(array $tx)
    {
        $advert = $this->document;
        switch($tx["status"]){
            case "COMPLETED":
                $advert->status = config("constants.ADVERT_STATES.PAID");
                $current_date = now();
                $advert->ad_paid_at = $current_date;
                $advert->save();
                $user = $advert->createdBy;
                $user->notify(new AdvertSubmittedToClient($advert));
                // Get all registrars
                $registrars = User::where('is_registrar', 1)->get();
                // Send notification to each registrar
                foreach ($registrars as $registrar) {
                    $registrar->notify(new AdvertSubmittedToRegistrar($advert));
                }
                break;
            case "FAILED":
                $advert->status = config("constants.ADVERT_STATES.PAYMENT FAILED");
                $advert->ad_payment_notes = $tx["notes"];
                $advert->save();
                break;
            default:
                break;
        }
    }
}