<x-filament::page>
    <div class="w-full flex flex-col gap-10 justify-center items-center">
        <form wire:submit.prevent="create"  class="lg:w-[50%] w-full">
            {{ $this->form }}
            <x-filament::button type="submit" form="create" >
                {{ __('Create report') }}
            </x-filament::button>
        </form>
    </div>
</x-filament::page>
