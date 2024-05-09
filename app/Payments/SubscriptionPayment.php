<?php
namespace App\Payments;

use App\Document;
use App\Payments\BasePayment;
use App\Subscription;

class SubscriptionPayment extends BasePayment{

    private Document|Subscription $document;
    
    public function setDocument(Document|Subscription $document)
    {
        $this->document = $document;
    }
    public function setStatus(array $tx)
    {
        $subscription = $this->document;
        switch($tx["status"]){
            case "COMPLETED":
                $subscription->sub_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $subscription->status = config("constants.SUB_STATUSES.ACTIVE");
                $current_date = now();
                $subscription->sub_start_date = clone $current_date;
                $subscription->sub_end_date = $current_date->addYear(1);
                $subscription->save();
                break;
            case "FAILED":
                $subscription->sub_payment_status = config("constants.SUB_PAY_STATES.FAILED");
                $subscription->status = config("constants.SUB_STATUSES.PAYMENT FAILED");
                $subscription->sub_payment_notes = $tx["notes"];
                $subscription->save();
                break;
            default:
                break;
        }
    }
}