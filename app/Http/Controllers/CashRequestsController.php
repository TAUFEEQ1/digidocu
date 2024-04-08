<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CashRequest;

class CashRequestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index(Request $request){
        $baseQ = CashRequest::with(['createdBy']);
        /** @var \App\User */
        $user = $request->user();

        if($user->is_hod){
            $baseQ->where("created_by",$user->id)->orWhere(function ($query){
                $query->where("status",config("constants.CASH_RQ_STATES.SUBMITTED"))->whereNull("hod_id");
            });
        }else if($user->is_finance_manager){
            $baseQ->where("created_by",$user->id)->orWhere(function ($query){
                $query->where("status",config("constants.CASH_RQ_STATES.HOD_APPROVED"))->whereNull("finance_manager_id");
            });
        }else if($user->is_internal_auditor){
            $baseQ->where("created_by",$user->id)->orWhere(function ($query){
                $query->where("status",config("constants.CASH_RQ_STATES.FINANCE_APPROVED"))->whereNull("internal_auditor_id");
            });
        }else if($user->is_managing_director){
            $baseQ->where("created_by",$user->id)->orWhere(function ($query){
                $query->where("status",config("constants.CASH_RQ_STATES.AUDITOR_APPROVED"))->whereNull("managing_director_id");
            });
        }else{
            $baseQ->where("created_by",$user->id);
        }
        $documents = $baseQ->orderBy('created_at', 'desc')->paginate(15);
        return view("leave_requests.index", compact("documents", "user"));
    }
    public function create(Request $request){

        return view("cash_requests.create");
    }
}
