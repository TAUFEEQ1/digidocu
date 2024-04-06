<?php

namespace App\Policies;

use App\LeaveUser;
use App\LeaveRequest;
use Illuminate\Auth\Access\HandlesAuthorization;


class LeaveRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function line_manage(LeaveUser $user,LeaveRequest $document){
        return $user->is_line_manager && $document->created_by != $user->id;
    }
    
}
