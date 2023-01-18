<x-filament::page>

    <div class="w-full flex flex-col gap-1 bg-warning-500 border border-warning-600 text-white py-3 px-4 text-sm rounded-lg">
        <span class="font-medium">{{ __('Important:') }}</span>
        <span class="font-normal">{{ __('Before you can import jira tickets you need to have all the referentials configured') }}</span>
    </div>

    <form wire:submit.prevent="import">
        {{ $this->form }}
    </form>

</x-filament::page>
