<div class="kanban-statuses">
    <div class="status-header"
         style="border-color: {{ $status['color'] }}66;">
        <span>{{ $status['title'] }}</span>
        @if($status['size'])
            {{ $status['size'] }} {{ __($status['size'] > 1 ? 'tickets' : 'ticket') }}
        @endif
    </div>
    <div class="status-container"
         data-status="{{ $status['id'] }}"
         id="status-records-{{ $status['id'] }}"
         style="border-color: {{ $status['color'] }}66;">
        @foreach($this->getRecords()->where('status', $status['id']) as $record)
            @include('partials.kanban.record')
        @endforeach

        <!-- href="{{ route('filament.resources.tickets.create', ['project' => request()->get('project')]) }}" -->
        @if($status['add_ticket'])
            <a class="create-record hover:cursor-pointer"
               wire:click="createTicket"
               target="_blank">
                <x-heroicon-o-plus class="w-4 h-4" /> {{ __('Create ticket') }}
            </a>

            @if($ticketToEdit || $ticket)
                <!-- Epic modal -->
                <div class="dialog-container">
                    <div class="dialog dialog-xl dark:bg-gray-900">
                        <div class="dialog-header">
                            <div class="flex justify-between items-center pb-3">
                                <p class="text-2xl font-bold">
                                    {{ $ticketToEdit ? __('Edit ticket') : __('Create ticket') }}
                                </p>

                                @if ($ticketToEdit)
                                    <div class="cursor-pointer z-50">
                                        <a
                                            href="{{ route('filament.resources.tickets.view', $record['id']) }}"
                                            target="_blank"
                                            class="record-title"
                                            title="{{ __('View ticket in a new tab') }}"
                                        >
                                            @svg('heroicon-s-external-link', 'h-5 w-5')
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="dialog-content">
                            @livewire('road-map.issue-form', [
                                'project' => null,
                                'ticketToEdit' => $ticketToEdit ?: null,
                            ])
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
