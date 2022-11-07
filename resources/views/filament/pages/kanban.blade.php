<x-filament::page>

    <div class="mx-auto w-full" wire:ignore>
        <details class="w-full bg-white open:bg-gray-200 duration-300">
            <summary
                class="relative w-full bg-inherit px-5 py-3 text-base cursor-pointer text-gray-500">
                {{ __('Filters') }}
            </summary>
            <div class="bg-white px-5 py-3">
                <form>
                    {{ $this->form }}
                </form>
            </div>
        </details>
    </div>

    <div class="kanban-container">

        @foreach($this->getStatuses() as $status)
            @include('partials.kanban.status')
        @endforeach

    </div>

    @push('scripts')
        <script src="{{ asset('js/Sortable.js') }}"></script>
        <script>

            (() => {
                let record;
                @foreach($this->getStatuses() as $status)
                    record = document.querySelector('#status-records-{{ $status['id'] }}');

                    Sortable.create(record, {
                        group: {
                            name: 'status-{{ $status['id'] }}',
                            pull: true,
                            put: true
                        },
                        handle: '.handle',
                        animation: 100,
                        onEnd: function (evt) {
                            Livewire.emit('recordUpdated',
                                +evt.clone.dataset.id, // id
                                +evt.newIndex, // newIndex
                                +evt.to.dataset.status, // newStatus
                            );
                        },
                    })
                @endforeach
            })();
        </script>
    @endpush

</x-filament::page>
