<form wire:submit.prevent="submit">
    {{ $this->form }}

    <div class="dialog-buttons">
        <button type="submit" wire:loading.attr="disabled">
            {{ __('Save') }}
        </button>
        <button type="button" wire:click="cancel" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </button>
    </div>
</form>
