<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

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
        Gate::before(function ($user): ?bool {
            return $user->hasRole('super_admin') ? true : null;
        });

        View::composer('components.front.layouts.app', function (ViewInstance $view): void {
            $settings = SiteSetting::current();

            $view->with([
                'settings' => $settings,
                'siteLogoUrl' => $settings->siteLogoUrl(),
            ]);
        });
    }
}
