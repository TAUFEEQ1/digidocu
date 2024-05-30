<?php

namespace App\Payments;
use App\Payments\SubscriptionPayment;
use App\Payments\AdvertPayment;
use App\Document;
use App\PublicationBuyer;
use App\Payments\PublicationPayment;
use Illuminate\Support\Facades\Log;

class PaymentFactory{
    public static function make(string $reference){
        $baseQ = Document::where("sub_payment_ref",$reference)->orWhere("ad_payment_ref",$reference);
        if(!$baseQ->exists()){
            $document = PublicationBuyer::where("payment_ref",$reference)->first();
            $payment = new PublicationPayment();
            $payment->setDocument($document);
            return $payment;
        }
        $document = $baseQ->first();
        if($document->category==config("constants.DOC_TYPES.ADVERT")){
            $payment = new AdvertPayment();
            $payment->setDocument($document);
        }
        else{
            $payment = new SubscriptionPayment();
            $payment->setDocument($document);
        }
        return $payment;
    }
}