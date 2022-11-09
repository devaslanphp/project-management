<x-filament-breezy::auth-card action="register">
    <div class="w-full flex justify-center">
        <x-filament::brand />
    </div>

    <div>
        <h2 class="font-bold tracking-tight text-center text-2xl">
            {{ __('filament-breezy::default.registration.heading') }}
        </h2>
        <p class="mt-2 text-sm text-center">
            {{ __('filament-breezy::default.or') }}
            <a class="text-primary-600" href="{{route('filament.auth.login')}}">
                {{ strtolower(__('filament::login.heading')) }}
            </a>
        </p>
    </div>

    {{ $this->form }}

    <x-filament::button type="submit" class="w-full">
        {{ __('filament-breezy::default.registration.submit.label') }}
    </x-filament::button>

    @if(config('filament-socialite.enabled'))
        <x-filament-socialite::buttons />
    @endif
</x-filament-breezy::auth-card>
