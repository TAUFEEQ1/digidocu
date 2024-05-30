<?php
namespace App\Payments;
use App\Payments\BasePayment;
use App\PublicationBuyer;
use App\Publication;
use App\Document;

class PublicationPayment extends BasePayment{

    private Document|PublicationBuyer $document;

    public function setDocument(Document|PublicationBuyer $document)
    {
        $this->document = $document;
    }
    public function setStatus(array $tx)
    {
        $publication_buyer = $this->document;
        switch($tx["status"]){
            case "COMPLETED":
                $publication_buyer->status = config("constants.ADVERT_STATES.PAID");
                $current_date = now();
                $publication_buyer->paid_at = $current_date;
                $publication_buyer->save();
                break;
            case "FAILED":
                $publication_buyer->status = config("constants.ADVERT_STATES.PAYMENT FAILED");
                $publication_buyer->payment_notes = $tx["notes"];
                $publication_buyer->save();
                break;
            default:
                break;
        }
    }
}