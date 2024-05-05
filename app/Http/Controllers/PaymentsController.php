<?php

namespace App\Http\Controllers;

use App\GovPayApi;
use Illuminate\Http\Request;
use App\Subscription;
use Illuminate\Support\Facades\Log;

use App\Document;
use App\Payments\PaymentFactory;


class PaymentsController extends Controller
{
    //

    public function callback(Request $request){
        $reference = $request->json("payload")["internal_reference"];
        $tx_status = $request->json("payload")["transaction_status"];
        $tx_notes = $request->json("payload")["status_message"];
        $api = new GovPayApi([]);
        $event = $request->json("event");

        $payment = PaymentFactory::make($reference);

        if($event == "transaction.charges"){
            $api->confirm($reference);
        }elseif($event == "transaction.completed"){
            $tx = ["status"=>$tx_status,"notes"=>$tx_notes];
            $payment->setStatus($tx);
        }elseif($event == "transaction.failed"){
            $tx = ["status"=>$tx_status,"notes"=>$tx_notes];
            $payment->setStatus($tx);
        }
        return ["message"=>"Callback received"];
    }
}
