<?php
namespace App\Payments;
use App\Payments\BasePayment;
use App\Advert;
use App\Document;

class AdvertPayment extends BasePayment{

    private Advert $document;

    public function setDocument(Document $document)
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