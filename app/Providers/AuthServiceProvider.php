<?php

namespace App\Providers;

use App\Document;
use App\User;
use App\Letter;
use App\LeaveUser;
use App\Policies\DocumentPolicy;
use App\Policies\LeaveRequestPolicy;
use App\Policies\LetterPolicy;
use App\Policies\TagPolicy;
use App\Tag;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Model' => 'App\Policies\ModelPolicy',
        User::class => LetterPolicy::class,
        LeaveUser::class => LeaveRequestPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->is_super_admin ? true : null;
        });
    }
}
