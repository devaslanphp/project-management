<x-filament::layouts.card>

    <form wire:submit.prevent="validateAccount">

        <div class="mb-10">
            <h2 class="font-bold tracking-tight text-center text-2xl">
                {{ __('Verify my account') }}
            </h2>
            <p class="mt-2 text-sm text-center">
                {{ __('Use the following form to choose your account password and validate your account creation') }}
            </p>
        </div>

        {{ $this->form }}

        <x-filament::button type="submit" class="w-full mt-5">
            {{ __('Verify') }}
        </x-filament::button>

    </form>


</x-filament::layouts.card>
