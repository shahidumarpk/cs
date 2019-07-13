<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Lead;
use App\Policies\LeadPolicy;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Model' => 'App\Policies\ModelPolicy',
		'App\Lead' => 'App\Policies\LeadPolicy',
		
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
		Gate::define('edit-lead',function($user,$lead){
			return $user->id === $lead->user_id;
		});
		
		Gate::define('isSuperAdmin',function($user){
			return $user->user_type === 1;
		});
    }
}
