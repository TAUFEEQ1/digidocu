<?php

namespace App\Http\Controllers;

use App\Document;
use App\Activity;
use App\Letter as GlobalLetter;
use App\User;
use Illuminate\Http\Request;
use Log;

class WelcomeController extends AppBaseController
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** @var \App\User */
        $user = $request->user();
        $letter_alias = config('constants.DOC_TYPES.LETTER');
        $leave_rqs = config('constants.DOC_TYPES.LEAVE_REQUESTS');
        $documents = Document::whereIn("category", [$letter_alias, $leave_rqs])->where("created_by", $user->id)->paginate(3);
        $doc_ids = GlobalLetter::where("created_by",$user->id)->orWhere("assigned_to",$user->id)
        ->orWhere("executed_by",$user->id)->orWhere("managed_by",$user->id)->pluck("id");
        $activities = Activity::with(['createdBy', 'document'])
            ->orWhereHas('document', function ($query) use ($user) {
                // letters
                $query->where('created_by', $user->id)
                    ->orWhere('assigned_to', $user->id)
                    ->orWhere('executed_by', $user->id)
                    ->orWhere('managed_by', $user->id);
            })->orWhereHas('document',function ($query) use($user){
                // cash requests
                if($user->is_hod){
                    $query->where("cr_hod_id",$user->id)->orWhere("status",config("constants.CASH_RQ_STATES.SUBMITTED"));
                }else if($user->is_finance_manager){
                    $query->where("cr_finance_manager_id",$user->id)->orWhere("status",config("constants.CASH_RQ_STATES.HOD_APPROVED"));
                }else if($user->is_internal_auditor){
                    $query->where("cr_internal_auditor_id",$user->id)->orWhere("status",config("constants.CASH_RQ_STATES.FINANCE_APPROVED"));
                }else{
                    $query->where("cr_managing_director_id",$user->id)->orWhere("status",config("constants.CASH_RQ_STATES.AUDITOR_APPROVED"));
                }
            })->orWhereHas("document",function ($query) use ($user){
                // leave requests
                if($user->is_line_manager){
                    $query->where("status",config("constants.LEAVE_RQ_STATES.SUBMITTED"))->where("lv_line_manager_id",$user->id);
                }else if($user->is_hr_manager){
                    $query->where("status", config('constants.LEAVE_RQ_STATES.LN_MGR_APPROVED'));
                }else{
                    $query->where("status",config("constants.LEAVE_RQ_STATES.HR_MGR_APPROVED"));
                }
            });
        if ($request->has('activity_range')) {
            $dates = explode("to", $request->get('activity_range'));
            $activities->whereDate('created_at', '>=', $dates[0] ?? '');
            $activities->whereDate('created_at', '<=', $dates[1] ?? '');
        }
        $activities = $activities->orderByDesc('created_at')->paginate(4);
        return view('new_home', compact('documents', 'activities'));
    }

    public function search(Request $request)
    {
        // Retrieve the search query and category from the request
        $query = $request->input('query');
        $category = $request->input('category');

        // Start with querying all documents
        $documents = Document::query();

        // Apply filters if they are provided
        if ($query) {
            $documents->where(function ($q) use ($query) {
                $q->where('category', 'like', "%$query%")
                    ->orWhere('status', 'like', "%$query%")
                    ->orWhere('created_at', 'like', "%$query%");
            });
        }

        if ($category) {
            $documents->where('category', $category);
        }

        // Paginate the filtered documents
        $documents = $documents->paginate(10);

        // Return the view with the filtered documents
        return view('new_home', compact('documents'));
    }


    public function welcome()
    {
        \Artisan::call("inspire");
        $quotes = \Artisan::output();
        return view('welcome', compact('quotes'));
    }
}
