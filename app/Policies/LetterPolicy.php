<?php

namespace App\Policies;

use App\CashRequest;
use App\User;
use App\Letter;
use App\LeaveRequest;

use Illuminate\Auth\Access\HandlesAuthorization;

class LetterPolicy
{
    use HandlesAuthorization;

    public function scan_letters(User $user){
        return $user->is_registry_member;
    }
    public function execute_letters(User $user){
        return $user->is_executive_secretary;
    }
    public function discard_letters(User $user){
        return $user->is_executive_secretary||$user->is_managing_director;
    }
    public function manage_letters(User $user){
        return $user->is_managing_director;
    }
    public function assign_letters(User $user){
        return $user->is_executive_secretary;
    }
    public function respond_letters(User $user, Letter $letter){
        return $letter->assigned_to == $user->id;
    }

    public function line_manage_leave_requests(User $user){
        return $user->is_line_manager;
    }
    public function hr_manage_leave_requests(User $user){
        return $user->is_hr_manager;
    }
    public function md_manage_leave_requests(User $user){
        return $user->is_managing_director;
    }
    public function review_cash_request(User $user,CashRequest $cashRequest){
        if($cashRequest->status == config("constants.CASH_RQ_STATES.SUBMITTED")){
            return $user->is_hod;
        }else if($cashRequest->hod_id && !$cashRequest->finance_manager_id){
            return $user->is_finance_manager;
        }else if($cashRequest->finance_manager_id && !$cashRequest->internal_auditor_id){
            return $user->is_internal_auditor;
        }else if($cashRequest->internal_auditor_id && !$cashRequest->managing_director_id){
            return $user->is_managing_director;
        }
    }
    public function hod_review_cr(User $user){
        return $user->is_hod;
    }
}
