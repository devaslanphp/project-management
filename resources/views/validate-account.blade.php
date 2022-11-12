<x-filament::layouts.base :title="__('Verify my account')">
    @livewire('validate-account', ['user' => $user])
</x-filament::layouts.base>
