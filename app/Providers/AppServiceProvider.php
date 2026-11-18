<?php

namespace App\Providers;

use App\Support\AppCache;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        $this->configureRateLimiting();

        View::composer('*', function ($view) {
            try {
                $view->with('settings', AppCache::settings());
                $view->with('navMenus', AppCache::menus());
            } catch (\Throwable) {
                $view->with('settings', null);
                $view->with('navMenus', collect());
            }
        });

        Paginator::useBootstrapFive();
    }

    protected function configureRateLimiting(): void
    {
        $limits = config('rate_limiting');

        RateLimiter::for('login', function (Request $request) use ($limits) {
            return Limit::perMinute($limits['login']['max_attempts'])
                ->by(Str::lower((string) $request->input('email')).'|'.$request->ip());
        });

        RateLimiter::for('otp', function (Request $request) use ($limits) {
            return Limit::perMinute($limits['otp']['max_attempts'])
                ->by((string) $request->input('phone', $request->ip()));
        });

        RateLimiter::for('payment', function (Request $request) use ($limits) {
            return Limit::perMinute($limits['payment']['max_attempts'])
                ->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('admin-mutations', function (Request $request) use ($limits) {
            return Limit::perMinute($limits['admin_mutations']['max_attempts'])
                ->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
