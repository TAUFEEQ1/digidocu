<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;

class PaymentsController extends Controller
{
    //
    private function setStatus(Subscription $subscription,array $tx){
        switch($tx["status"]){
            case "COMPLETE":
                $subscription->sub_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $subscription->status = config("constants.SUB_STATUSES.ACTIVE");
                $current_date = now();
                $subscription->sub_start_date = $current_date;
                $subscription->sub_end_date = $current_date->addYear(1);
                $subscription->save();
                break;
            case "FAILED":
                $subscription->sub_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $subscription->status = config("constants.SUB_STATUSES.ACTIVE");
                $subscription->sub_payment_notes = $tx["notes"];
                $subscription->save();
                break;
            default:
                break;
        }
    }

    public function callback(Request $request){
        $reference = $request->json("payload")["internal_reference"];
        $tx_status = $request->json("payload")["transaction_status"];
        $tx_notes = $request->json("payload")["status_message"];
        $tx = ["status"=>$tx_status,"notes"=>$tx_notes];
        $subscription = Subscription::where("sub_payment_ref",$reference)->first();
        $this->setStatus($subscription,$tx);
        
        return ["message"=>"Callback received"];
    }
}
