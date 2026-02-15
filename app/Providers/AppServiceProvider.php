<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        try {
            // Cache settings for 60 minutes to avoid DB calls on every request
            $schoolSettings = \Illuminate\Support\Facades\Cache::remember('school_settings', 3600, function () {
                // Check if table exists implicitly by try-catch or explicit check if cache miss
                // We skip Schema::hasTable for performance, relying on catch block if table missing
                return \App\Models\Setting::all()->pluck('value', 'key');
            });
            
            \Illuminate\Support\Facades\View::share('schoolSettings', $schoolSettings);
        } catch (\Exception $e) {
            // If table doesn't exist or DB is down, share empty array to prevent view errors
            \Illuminate\Support\Facades\View::share('schoolSettings', []);
        }
    }
}
