<?php
namespace App\Enums;

abstract class LeaveStates{
    public static function ordered(){
        return [
            config("constants.LEAVE_RQ_STATES.SUBMITTED"),
            config("constants.LEAVE_RQ_STATES.LN_MGR_APPROVED"),
            config("constants.LEAVE_RQ_STATES.HR_MGR_APPROVED"),
            config("constants.LEAVE_RQ_STATES.MG_DIR_APPROVED")
        ];
    }
}