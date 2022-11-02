<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Foundation\Vite;
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
        // Register custom Filament theme
        Filament::serving(function () {
            Filament::registerTheme(
                app(Vite::class)('resources/css/filament.css'),
            );
        });

        // Register tippy styles
        Filament::registerStyles([
            'https://unpkg.com/tippy.js@6/dist/tippy.css',
        ]);

        // Register tippy scripts
        Filament::registerScripts([
            'https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@0.x.x/dist/cdn.min.js',
        ], true);

        // Register navigation groups
        Filament::registerNavigationGroups([
            __('Permissions'),
        ]);
    }
}
