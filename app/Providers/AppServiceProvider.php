<?php

namespace App\Providers;

use App\Listeners\SocialRegistration;
use App\Settings\GeneralSettings;
use DutchCodingCompany\FilamentSocialite\Events\Registered as SocialRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureApp();

        if (env('APP_FORCE_HTTPS') ?? false) {
            URL::forceScheme('https');
        }

        Event::listen(Registered::class, SendEmailVerificationNotification::class);
        Event::listen(SocialRegistered::class, SocialRegistration::class);
    }

    private function configureApp(): void
    {
        try {
            $settings = app(GeneralSettings::class);
            Config::set('app.locale', $settings->site_language ?? config('app.fallback_locale'));
            Config::set('app.name', $settings->site_name ?? env('APP_NAME'));
            Config::set(
                'app.logo',
                $settings->site_logo ? asset('storage/' . $settings->site_logo) : asset('favicon.ico')
            );
            Config::set('filament-breezy.enable_registration', $settings->enable_registration ?? false);
            Config::set('filament-socialite.registration', $settings->enable_registration ?? false);
            Config::set('filament-socialite.enabled', $settings->enable_social_login ?? false);
            Config::set('system.login_form.is_enabled', $settings->enable_login_form ?? false);
            Config::set('services.oidc.is_enabled', $settings->enable_oidc_login ?? false);
        } catch (\Exception $e) {
            // Error: No database configured yet, or missing settings migrations
        }
    }
}
