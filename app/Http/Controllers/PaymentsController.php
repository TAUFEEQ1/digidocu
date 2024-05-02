<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;

class PaymentsController extends Controller
{
    //
    private function setStatus(Subscription $subscription,string $tx_status){
        switch($tx_status){
            case "COMPLETE":
                $subscription->sub_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $subscription->status = config("constants.SUB_STATUSES.ACTIVE");
                $subscription->save();
                break;
            case "FAILED":
                $subscription->sub_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $subscription->status = config("constants.SUB_STATUSES.ACTIVE");
                $subscription->save();
                break;
            default:
                break;
        }
    }

    public function callback(Request $request){
        $reference = $request->json("payload")["internal_reference"];
        $tx_status = $request->json("payload")["transaction_status"];
        $subscription = Subscription::where("sub_payment_ref",$reference)->first();
        $this->setStatus($subscription,$tx_status);
        
        return ["message"=>"Callback received"];
    }
}
