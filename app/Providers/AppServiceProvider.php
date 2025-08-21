<?php

namespace App\Providers;

use App\Http\View\Composers\NotificationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share notification data with all views that use the master layout
        View::composer(['layout.master', 'index', 'send-mail'], NotificationComposer::class);

        // Also share with all views globally (as a fallback)
        View::share('renewalNotifications', []);
        View::share('notificationCounts', ['total' => 0]);
        View::share('hasCriticalNotifications', false);
    }
}
