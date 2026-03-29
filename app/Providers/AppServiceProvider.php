<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
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
        // Configure application
        $this->configureApp();

        // Force HTTPS over HTTP
        if (env('APP_FORCE_HTTPS') ?? false) {
            URL::forceScheme('https');
        }
    }

    private function configureApp(): void
    {
        try {
            $settings = app(GeneralSettings::class);
            Config::set('app.locale', $settings->site_language ?? config('app.fallback_locale'));
            Config::set('app.name', $settings->site_name ?? env('APP_NAME'));
            Config::set('filament.brand', $settings->site_name ?? env('APP_NAME'));
            Config::set(
                'app.logo',
                $settings->site_logo ? asset('storage/' . $settings->site_logo) : asset('favicon.ico')
            );
            Config::set('filament-breezy.enable_registration', $settings->enable_registration ?? false);
            Config::set('filament-socialite.registration', $settings->enable_registration ?? false);
            Config::set('filament-socialite.enabled', $settings->enable_social_login ?? false);
            Config::set('system.login_form.is_enabled', $settings->enable_login_form ?? false);
            Config::set('services.oidc.is_enabled', $settings->enable_oidc_login ?? false);
        } catch (QueryException $e) {
            // Error: No database configured yet
        }
    }
}
