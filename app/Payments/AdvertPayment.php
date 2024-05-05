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
                $advert->ad_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $advert->status = config("constants.SUB_STATUSES.ACTIVE");
                $current_date = now();
                $advert->ad_paid_at = $current_date;
                $advert->save();
                break;
            case "FAILED":
                $advert->ad_payment_status = config("constants.SUB_PAY_STATES.FAILED");
                $advert->status = config("constants.SUB_STATUSES.PAYMENT FAILED");
                $advert->ad_payment_notes = $tx["notes"];
                $advert->save();
                break;
            default:
                break;
        }
    }
}