<x-filament::page>

    @if($project->currentSprint)

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
    @else
        <div class="w-full flex flex-col">
            <span class="text-gray-500 text-lg font-medium">
                {{ __('No active sprint for this project!') }}
            </span>
            @if(auth()->user()->can('update', $project))
                <span class="text-gray-500 text-sm">
                    {{ __("Click the below button to manage project's sprints") }}
                </span>
                <a href="{{ route('filament.resources.projects.view', $project) }}"
                   class="px-3 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded mt-3 w-fit">
                    {{ __('Manage sprints') }}
                </a>
            @else
                <span class="text-gray-500 text-sm">
                    {{ __("If you think a sprint should be started, please contact an administrator") }}
                </span>
            @endif
        </div>
    @endif

</x-filament::page>
