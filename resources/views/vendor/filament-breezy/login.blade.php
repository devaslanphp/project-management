<x-filament-breezy::auth-card action="authenticate">
    @php
        $logoPaths = [
            'clawbot' => null,
            'prince' => null,
        ];

        foreach (['png', 'svg', 'jpg', 'jpeg', 'webp'] as $extension) {
            if (! $logoPaths['clawbot'] && file_exists(public_path("img/clawbot-logo.{$extension}"))) {
                $logoPaths['clawbot'] = asset("img/clawbot-logo.{$extension}");
            }

            if (! $logoPaths['prince'] && file_exists(public_path("img/prince-logo.{$extension}"))) {
                $logoPaths['prince'] = asset("img/prince-logo.{$extension}");
            }
        }
    @endphp

    <div class="space-y-6">
        <div class="flex items-center justify-center gap-4">
            <div class="flex flex-col items-center gap-2">
                @if($logoPaths['clawbot'])
                    <img
                        src="{{ $logoPaths['clawbot'] }}"
                        alt="Clawbot logo"
                        class="h-16 w-auto object-contain"
                    >
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-600 text-lg font-bold text-white">
                        C
                    </div>
                @endif

                <span class="text-sm font-semibold tracking-wide text-gray-600">
                    Clawbot
                </span>
            </div>

            <div class="h-10 w-px bg-gray-200"></div>

            <div class="flex flex-col items-center gap-2">
                @if($logoPaths['prince'])
                    <img
                        src="{{ $logoPaths['prince'] }}"
                        alt="Prince logo"
                        class="h-16 w-auto object-contain"
                    >
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-900 text-lg font-bold text-white">
                        P
                    </div>
                @endif

                <span class="text-sm font-semibold tracking-wide text-gray-600">
                    Prince
                </span>
            </div>
        </div>

        <div class="w-full flex justify-center">
            <x-filament::brand />
        </div>
    </div>

    <h2 class="font-bold tracking-tight text-center text-2xl">
        {{ __('filament::login.heading') }}
    </h2>

    @if(session()->has('oidc_error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">{{ __('OIDC Connect error') }}</span> {{ __('Invalid account!') }}
        </div>
    @endif

    @if(config('system.login_form.is_enabled'))
        <div>
            @if(config("filament-breezy.enable_registration"))
                <p class="mt-2 text-sm text-center">
                    {{ __('filament-breezy::default.or') }}
                    <a class="text-primary-600" href="{{route(config('filament-breezy.route_group_prefix').'register')}}">
                        {{ strtolower(__('filament-breezy::default.registration.heading')) }}
                    </a>
                </p>
            @endif
        </div>

        {{ $this->form }}

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <x-filament::button type="submit" class="w-full">
                {{ __('filament::login.buttons.submit.label') }}
            </x-filament::button>

            <x-filament::button type="reset" color="gray" class="w-full">
                {{ __('Cancel') }}
            </x-filament::button>
        </div>

        <div class="text-center">
            <a class="text-primary-600 hover:text-primary-700" href="{{route(config('filament-breezy.route_group_prefix').'password.request')}}">{{ __('filament-breezy::default.login.forgot_password_link') }}</a>
        </div>
    @endif

    @if(config('services.oidc.is_enabled'))
        <x-filament::button
            color="secondary"
            class="w-full"
            tag="a"
            :href="route('oidc.redirect')"
        >
            <div class="w-full flex items-center gap-2">
                <x-heroicon-o-login class="w-5 h-5" />
                {{ __('OIDC Connect') }}
            </div>
        </x-filament::button>
    @endif

    @if(config('filament-socialite.enabled'))
        <x-filament-socialite::buttons />
    @endif
</x-filament-breezy::auth-card>
