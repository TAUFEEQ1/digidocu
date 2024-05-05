<?php

namespace App\Payments;
use App\Payments\SubscriptionPayment;
use App\Payments\AdvertPayment;
use App\Document;

class PaymentFactory{
    public static function make(string $reference){
        $document = Document::where("sub_payment_ref",$reference)->orWhere("ad_payment_ref",$reference)->first();
        if($document->category==config("constants.DOC_TYPES.ADVERT")){
            $payment = new AdvertPayment();
            $payment->setDocument($document);
        }else{
            $payment = new SubscriptionPayment();
            $payment->setDocument($document);
        }
        return $payment;
    }
}