@if (count($providers))
    <div class="relative flex items-center justify-center text-center">
        <div class="absolute border-t border-gray-200 w-full h-px"></div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        @foreach($providers as $key => $provider)
            <x-filament::button
                color="secondary"
                :icon="$provider['icon'] ?? null"
                tag="a"
                :href="route('socialite.oauth.redirect', $key)"
            >
                {{ $provider['label'] }}
            </x-filament::button>
        @endforeach
    </div>
@else
    <span></span>
@endif
