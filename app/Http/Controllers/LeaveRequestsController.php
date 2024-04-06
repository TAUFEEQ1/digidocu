<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveRequest;
use App\User;
use Str;

class LeaveRequestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index(Request $request){
        /** @var \App\User */
        $user = $request->user();
        $baseQ = LeaveRequest::with(['createdBy','lineManager','hrManager']);
        if ($user->is_line_manager) {
            $baseQ->where('lv_line_manager_id',$user->id);
        }else if($user->is_managing_director){
            $baseQ->where('lv_managing_director_id',$user->id);
        }
        else{
            $baseQ->where('created_by',$user->id);
        }
        $documents = $baseQ->orderBy('created_at', 'desc')->paginate(15);
        return view("leave_requests.index", compact("documents", "user"));
    }

    public function create(Request $request){
        /** @var \App\User */
        $user = $request->user();
        $senior_managers = User::where("is_line_manager",true)->get()->pluck('id','name');
        return view("leave_requests.create",compact("user","senior_managers"));
    }
    public function store(Request $request){
        /** @var \App\User */
        $user = $request->user();
        $data = $request->all();
        $ref_no = Str::uuid();
        $lv = LeaveRequest::create([
            "lv_reference_number"=>$ref_no,
            "name"=>$ref_no,
            "lv_designation"=>$data['lv_designation'],
            "lv_department"=>$data['lv_department'],
            "lv_type"=>$data["lv_type"],
            "lv_line_manager_id"=>$data['lv_line_manager_id'],
            "lv_start_date"=>$data['lv_start_date'],
            "lv_end_date"=>$data['lv_end_date'],
            'status' => config('constants.LEAVE_RQ_STATES.SUBMITTED'),
        ]);
        $lv->created_by = $user->id;
        $lv->newActivity('Leave Request Submitted');
        $lv->save();

        return redirect()->route('leave_requests.index');
    }
    public function show(int $id,Request $request){
        $leave_request = LeaveRequest::find($id);
        /** @var \App\User */
        $user = $request->user();

    }
}
