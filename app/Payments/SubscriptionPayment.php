<?php

namespace App\Payments;

use App\Document;
use App\Payments\BasePayment;
use App\Subscription;
use Carbon\Carbon;
use SubscriptionFailed;
use SubscriptionSuccessful;

class SubscriptionPayment extends BasePayment
{

    private Document|Subscription $document;

    public function setDocument(Document|Subscription $document)
    {
        $this->document = $document;
    }
    public function getEndDate(Carbon $current_date)
    {
        $sub_type = $this->document->sub_type;

        switch ($sub_type) {
            case 'One-Off':
                $current_date->addDay();
                break;
            case '3-Months':
                $current_date->addMonths(3);
                break;
            case '6-Months':
                $current_date->addMonths(6);
                break;
            default:
                $current_date->addYear();
                break;
        }
        return $current_date;
    }
    public function setStatus(array $tx)
    {
        $subscription = $this->document;
        switch ($tx["status"]) {
            case "COMPLETED":
                $subscription->sub_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $subscription->status = config("constants.SUB_STATUSES.ACTIVE");
                $current_date = now();

                $subscription->sub_start_date = clone $current_date;
                $subscription->sub_end_date = $this->getEndDate($current_date);
                $subscription->save();
                $user = $subscription->createdBy;
                $user->notify(new SubscriptionSuccessful($subscription));
                break;
            case "FAILED":
                $subscription->sub_payment_status = config("constants.SUB_PAY_STATES.FAILED");
                $subscription->status = config("constants.SUB_STATUSES.PAYMENT FAILED");
                $subscription->sub_payment_notes = $tx["notes"];
                $subscription->save();
                $user = $subscription->createdBy;
                $user->notify(new SubscriptionFailed($tx["notes"]));
                break;
            default:
                break;
        }
    }
}
