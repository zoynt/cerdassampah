<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\Report;           // <-- Import model Report
use App\Observers\ReportObserver; // <-- Import ReportObserver

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
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
    }
            Report::observe(ReportObserver::class);
    }
}
