<?php
namespace App\Http\Controllers;

use App\Jobs\PaymentProcessor;
use Illuminate\Http\Request;

use App\Subscription;

class SubscriptionsController extends Controller{

    public function index(Request $request){
        /** @var \App\User */
        $user = $request->user();
        $baseQ = Subscription::query();
        if($user->is_client){
            $baseQ->where("created_by",$user->id);
        }
        $documents = $baseQ->orderByDesc('id')->paginate(25);

        return view("subscriptions.index",compact("documents","user"));
    }

    public function create(Request $request){
        /** @var \App\User */
        $user = $request->user();

        return view("subscriptions.create",compact("user"));
    }
    public function store(Request $request){
        $user = $request->user();
        $networks = config("constants.MOBILE_NETWORKS");
        $sub_types = config("constants.SUB_TYPES");
        $sub_fees = config("constants.SUB_FEES");
        /** @var \App\Subscription */
        $subscription = Subscription::create(
            [
                "name"=>"Subscription from ".$user->name,
                "status"=>config("constants.SUB_STATUSES.PENDING PAYMENT"),
                "sub_payment_status"=>config("constants.SUB_PAY_STATES.PENDING"),
                "category"=>config("constants.DOC_TYPES.SUBSCRIPTION"),
                "created_by"=>$user->id,
                "sub_amount"=>$sub_fees[(int)$request->input("sub_type")],
                "sub_type" => $sub_types[(int)$request->input("sub_type")],
                "sub_payment_method"=>"MOBILE",
                "sub_payment_mobile_network"=>$networks[(int)$request->input("sub_payment_mobile_network")],
                "sub_payment_mobile_no"=>$request->input("sub_payment_mobile_no")
            ]
        );
        PaymentProcessor::dispatch($subscription);
        $subscription->newActivity("Subscription created by ".$user->name);
        $subscription->save();

        return redirect()->route("subscriptions.index");
    }
    public function show(int $id, Request $request){
        $document = Subscription::find($id);
        $user = $request->user();
        return view("subscriptions.show",compact("document","user"));
    }
}
