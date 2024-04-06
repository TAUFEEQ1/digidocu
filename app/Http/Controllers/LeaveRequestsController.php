<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveRequest;
use App\User;
use Log;
use Str;
use Carbon\Carbon;

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
        if($user->is_line_manager){
            $baseQ->where("created_by",$user->id)->orWhere(function ($query) use ($user){
                $query->where("status",config("constants.LEAVE_RQ_STATES.SUBMITTED"))->where("lv_line_manager_id",$user->id);
            });
        }elseif($user->is_hr_manager){
            $baseQ->where("created_by",$user->id)->orWhere("lv_hr_manager_id",$user->id)->orWhere(function ($query){
                $query->where("status", config('constants.LEAVE_RQ_STATES.LN_MGR_APPROVED'))->whereNull("lv_hr_manager_id");
            });
        }elseif($user->is_managing_director){
            $baseQ->where("created_by",$user->id)->orWhere("lv_managing_director_id",$user->id)->orWhere(function ($query){
                $query->where("status",config("constants.LEAVE_RQ_STATES.HR_MGR_APPROVED"))->whereNull("lv_managing_director_id");
            });
        }else{
            $baseQ->where("created_by",$user->id);
        }

        $documents = $baseQ->orderBy('created_at', 'desc')->paginate(15);
        return view("leave_requests.index", compact("documents", "user"));
    }

    public function create(Request $request){
        /** @var \App\User */
        $user = $request->user();
        $senior_managers = User::where("is_line_manager",true)->get()->pluck('name','id');
        return view("leave_requests.create",compact("user","senior_managers"));
    }
    public function store(Request $request){
        /** @var \App\User */
        $user = $request->user();
        $data = $request->all();
        $ref_no = Str::uuid();
        $lv = LeaveRequest::create([
            "lv_reference_number"=>$ref_no,
            "name"=>"Leave Application by ".$user->name,
            "lv_designation"=>$data['lv_designation'],
            "lv_department"=>$data['lv_department'],
            "lv_type"=>$data["lv_type"],
            "lv_line_manager_id"=>$data['lv_line_manager_id'],
            "lv_start_date"=>$data['lv_start_date'],
            "lv_end_date"=>$data['lv_end_date'],
            'status' => config('constants.LEAVE_RQ_STATES.SUBMITTED'),
            "created_by"=>$user->id,
        ]);
        $lv->created_by = $user->id;
        $lv->category = config('constants.DOC_TYPES.LEAVE_REQUESTS');
        $lv->newActivity('Leave Request Submitted');
        $lv->save();

        return redirect()->route('leave_requests.index');
    }
    public function show(int $id,Request $request){
        $document = LeaveRequest::find($id);
        /** @var \App\User */
        $user = $request->user();

        return view("leave_requests.show",compact("user","document"));

    }
    private function countBusinessDays(Carbon $startDate, Carbon $endDate)
    {
        $businessDays = 0;
        $currentDate = $startDate->copy();
    
        while ($currentDate <= $endDate) {
            // Exclude weekends (Saturday and Sunday)
            if ($currentDate->isWeekday()) {
                $businessDays++;
            }
    
            // Move to the next day
            $currentDate->addDay();
        }
    
        return $businessDays;
    }
    public function review(int $id, Request $request){
        $document = LeaveRequest::find($id);
        /** @var \App\User */
        $user = $request->user();
        $data = $request->all();
        $vcomment = $data['vcomment']?$data['vcomment']:'NA';
        $action = $request->input('action');

        if ($action == config('constants.LEAVE_RQ_STATES.LN_MGR_APPROVED')) {
            $document->status = config('constants.LEAVE_RQ_STATES.LN_MGR_APPROVED');
            $document->lv_line_manager_notes = $vcomment;
            $document->lv_line_managed_at = now();
            $document->newActivity('Leave Request Approved by Line Mgr: '.$user->name);
            $document->save();
        }elseif($action == config('constants.LEAVE_RQ_STATES.LN_MGR_DENIED')){
            $document->status = config('constants.LEAVE_RQ_STATES.LN_MGR_DENIED');
            $document->lv_line_manager_notes = $vcomment;
            $document->lv_line_managed_at = now();
            $document->newActivity('Leave Request Denied by Line Mgr: '.$user->name);
            $document->save();
        }elseif($action == config('constants.LEAVE_RQ_STATES.HR_MGR_APPROVED')){
            $document->status = config('constants.LEAVE_RQ_STATES.HR_MGR_APPROVED');
            $document->lv_hr_manager_id = $user->id;
            $document->lv_hr_managed_at = now();

            $document->lv_hr_manager_notes = $request->input('vcomment','NA');
            $document->newActivity('Leave Request Approved by HR Mgr: '.$user->name);
            $document->save();
        }elseif($action == config('constants.LEAVE_RQ_STATES.HR_MGR_DENIED')){
            $document->status = config('constants.LEAVE_RQ_STATES.HR_MGR_DENIED');
            $document->lv_hr_manager_id = $user->id;
            $document->lv_hr_managed_at = now();
            $document->lv_hr_manager_notes = $vcomment;
            $document->newActivity('Leave Request Denied by HR Mgr: '.$user->name);
            $document->save();
        }
        elseif($action == config('constants.LEAVE_RQ_STATES.MG_DIR_APPROVED')){
            $document->status = config('constants.LEAVE_RQ_STATES.MG_DIR_APPROVED');
            $document->lv_managing_director_notes = $vcomment;
            $document->lv_managing_director_id = $user->id;
            $document->lv_managing_directed_at = now();
            $document->newActivity('Leave Request Approved by MD: '.$user->name);
            // reduce leave days
            /** @var \App\User */
            $applicant = $document->createdBy;
            $startDate = Carbon::parse($document->lv_start_date);
            $endDate = Carbon::parse($document->lv_end_date);
            $applicant->outstanding_leave_days -= $this->countBusinessDays($startDate,$endDate);
            $document->save();
        }elseif($action == config('constants.LEAVE_RQ_STATES.MG_DIR_DENIED')){
            $document->status = config('constants.LEAVE_RQ_STATES.MG_DIR_DENIED');
            $document->lv_managing_director_notes = $vcomment;
            $document->lv_managing_director_id = $user->id;
            $document->lv_managing_directed_at = now();
            $document->newActivity('Leave Request Denied by MD: '.$user->name);
            $document->save();
        }
        return redirect()->route('leave_requests.show', ['id' => $id]);
    }
}
