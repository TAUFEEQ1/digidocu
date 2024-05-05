<?php

namespace App\Http\Controllers;

use App\GovPayApi;
use Illuminate\Http\Request;
use App\Subscription;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    //
    private function setStatus(Subscription $subscription,array $tx){
        switch($tx["status"]){
            case "COMPLETED":
                $subscription->sub_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $subscription->status = config("constants.SUB_STATUSES.ACTIVE");
                $current_date = now();
                $subscription->sub_start_date = $current_date;
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

    public function callback(Request $request){
        $reference = $request->json("payload")["internal_reference"];
        $tx_status = $request->json("payload")["transaction_status"];
        $tx_notes = $request->json("payload")["status_message"];
        $api = new GovPayApi([]);
        $event = $request->json("event");
        if($event == "transaction.charges"){
            $api->confirm($reference);
        }elseif($event == "transaction.completed"){
            $tx = ["status"=>$tx_status,"notes"=>$tx_notes];
            $subscription = Subscription::where("sub_payment_ref",$reference)->first();
            $this->setStatus($subscription,$tx);
        }elseif($event == "transaction.failed"){
            $tx = ["status"=>$tx_status,"notes"=>$tx_notes];
            $subscription = Subscription::where("sub_payment_ref",$reference)->first();
            $this->setStatus($subscription,$tx);
        }
        return ["message"=>"Callback received"];
    }
}
