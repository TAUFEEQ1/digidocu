<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CashRequest;
use App\FileType;
use Str;

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
        return view("cash_requests.index", compact("documents", "user"));
    }
    public function create(Request $request){
        $user = $request->user();
        return view("cash_requests.create",compact("user"));
    }
    public function store(Request $request){
        $user = $request->user();
        $data = $request->all();
        $cash_request = CashRequest::create([
            "cr_amount"=>$data["amount"],
            "cr_department"=>$data["department"],
            "cr_reference_number"=>Str::uuid(),
            "status"=>config("constants.CASH_RQ_STATES.SUBMITTED"),
            "name"=>$data["title"],
            "cr_title"=>$data["title"],
            "cr_purpose" =>$data["purpose"],
            "created_by"=>$user->id
        ]);
        $cash_request->category = config("constants.DOC_TYPES.CASH_REQUEST");
        $cash_request->newActivity('Cash Request Submitted');
        $cash_request->save();

        // save file.
        $uploaded_file = $request->file('file_scan');
        $uploaded_file->store('files/original');
        $fileData['name'] =  $uploaded_file->getClientOriginalName();
        $fileData['created_by'] = $user->id;
        $fileData['file'] = $uploaded_file->hashName();
        $fileData['custom_fields'] = json_encode([]);
        $fileData['created_at'] = now();
        $fileData['updated_at'] = now();
        $file_type = FileType::where('name', config('constants.DOC_TYPES.RECEIPT_PDF'))->first();
        $fileData['file_type_id'] = $file_type->id;
        $fileData['document_id'] = $cash_request->id;

        $cash_request->files()->insert([$fileData]);
        return redirect()->route('cash_requests.index'); 
    }
    public function show(int $id, Request $request){
        

    }
}
