<div class="w-full flex flex-col gap-5">
    <form wire:submit.prevent="perform" class="w-full">
        {{ $this->form }}

        <button type="submit" wire:loading.prop="disabled"
                class="px-3 py-2 bg-primary-500 disabled:bg-gray-300 hover:bg-primary-600 text-white rounded mt-3">
            {{ __('Upload') }}
        </button>
    </form>

    <div class="w-full flex flex-col gap-1 pt-3">
        <div class="w-full">
            {{ $this->table }}
        </div>
    </div>
</div>
