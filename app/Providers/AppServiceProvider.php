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
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $lang = \App\Models\Setting::get('language');
                if ($lang) {
                    \Illuminate\Support\Facades\App::setLocale($lang);
                }
                
                $timezone = \App\Models\Setting::get('timezone');
                if ($timezone) {
                    date_default_timezone_set($timezone);
                    \Illuminate\Support\Facades\Config::set('app.timezone', $timezone);
                }
            }
        } catch (\Exception $e) {
            // Ignore during setup/migrations
        }
    }
}
