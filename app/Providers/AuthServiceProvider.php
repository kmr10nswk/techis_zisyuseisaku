<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use app\Models\Admin;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // 商品＋アカウント
        Gate::define('item_admin', function(Admin $admin){
            return ($admin->policy->item_admin === 1);
        });

        // 掲示板＋アカウント
        Gate::define('theread_admin', function(Admin $admin) {
            return ($admin->policy->theread_admin === 1);
        });

        // 全て
        Gate::define('all_admin', function(Admin $admin){
            Debugbar::addMessage($admin);
            return ($admin->policy->item_admin === 1 && $admin->policy->theread_admin === 1);
        });
    }
}
