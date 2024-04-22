<?php
namespace App\Http\Controllers;
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

        return view("subscriptions.index",compact("documents"));
    }
}