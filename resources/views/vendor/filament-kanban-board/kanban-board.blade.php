<x-filament::page>
    <div>
        <div>
            @includeIf($beforeKanbanBoardView)
        </div>

        <div class="{{ $styles['wrapper'] }}">
            @foreach($statuses as $status)
                @include($kanbanView, [
                    'status' => $status
                ])
            @endforeach
        </div>

        <div>
            @includeIf($afterKanbanBoardView)
        </div>

        <div wire:ignore>
            @includeWhen($sortable, 'filament-kanban-board::sortable', [
                'sortable' => $sortable,
                'sortableBetweenStatuses' => $sortableBetweenStatuses,
            ])
        </div>
    </div>
    @if($recordClickEnabled && $modalRecordClickEnabled)
        <x-filament-kanban-board::edit-modal-record/>
    @endif
</x-filament::page>
