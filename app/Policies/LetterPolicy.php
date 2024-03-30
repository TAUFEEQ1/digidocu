<?php

namespace App\Policies;

use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class LetterPolicy
{
    use HandlesAuthorization;

    public function scan_letters(User $user){
        return $user->is_registry_member;
    }

}
