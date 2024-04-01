<?php

namespace App\Policies;

use App\User;
use App\Letter;

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
}
