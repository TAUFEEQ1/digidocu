<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\LeaveRequest;
use App\Enums\LeaveStates;
use Carbon\Carbon;

class LeaveRosterController extends Controller
{
    //
    public function index(Request $request){
        $this->authorize('view_leave_roster',User::class);
        $user = $request->user();
        // All states above line manager
        $line_mgr_index = array_search(config("constants.LEAVE_RQ_STATES.LN_MGR_APPROVED"),LeaveStates::ordered());
        $line_mgd_states = array_slice(LeaveStates::ordered(),$line_mgr_index);
        $currentDate = Carbon::now();
        $documents = LeaveRequest::with(["createdBy"])->where("lv_line_manager_id",$user->id)
        ->whereIn("status",$line_mgd_states)->where("lv_end_date",'>=',$currentDate)->get();
        $documents = $documents->map(function ($document){
            return [
                "id"=>$document->id,
                "title"=>$document->name,
                "type"=>$document->lv_type,
                "status"=>$document->status,
                "created_by"=>$document->createdBy->name,
                "start"=>$document->lv_start_date,
                "end"=>$document->lv_end_date
            ];
        });
        return view("leave_roster.index",compact("documents"));
    }
}
