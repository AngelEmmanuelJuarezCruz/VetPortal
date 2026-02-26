<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Multi-tenant scoping
        Tenant::addGlobalScope('tenant', function ($builder) {
            if (Auth::check() && Auth::user()->tenant_id) {
                $builder->where('tenant_id', Auth::user()->tenant_id);
            }
        });
    }
}
