<?php

namespace App\Filament\Pages;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use InvadersXX\FilamentKanbanBoard\Pages\FilamentKanbanBoard;

class Kanban extends FilamentKanbanBoard
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments';

    protected static ?string $slug = 'kanban/{project}';

    public bool $sortable = true;

    public bool $sortableBetweenStatuses = true;

    protected static bool $shouldRegisterNavigation = false;

    public bool $recordClickEnabled = true;

    public Project $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    protected function styles(): array
    {
        return [
            'wrapper' => 'kanban-container',
            'kanbanWrapper' => 'kanban-wrapper',
            'kanban' => 'kanban',
            'kanbanHeader' => 'kanban-header',
            'kanbanFooter' => 'kanban-footer',
            'kanbanRecords' => 'kanban-records',
            'record' => 'record',
            'recordContent' => 'record-content',
        ];
    }

    protected function statuses(): Collection
    {
        return TicketStatus::all()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => $item->name,
                'bg-color' => $item->color . '11',
                'border-color' => $item->color . '33'
            ]);
    }

    protected function records(): Collection
    {
        return Ticket::where('project_id', $this->project->id)
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'title' => new HtmlString('
                    <div class="w-full flex flex-col gap-2 p-2">
                        <span class="text-lg font-medium text-gray-700">' . $item->name . '</span>
                        <div class="w-full flex justify-between items-center">
                            <span class="text-xs text-gray-700 font-normal">
                                ' . $item->project->name . '
                            </span>
                            ' . ($item->responsible ? '
                                <div class="w-8 h-8 rounded-full bg-gray-200 bg-cover bg-center"
                                     style="background-image: url(' . $item->responsible->avatarUrl . ')">
                                </div>
                            ' : '') . '
                        </div>
                    </div>
                '),
                'status' => $item->status_id
            ]);
    }

    public function onStatusChanged($recordId, $statusId, $fromOrderedIds, $toOrderedIds): void
    {
        $user = auth()->user();
        $ticket = Ticket::where('id', $recordId)->first();
        if (
            $user->can('Update ticket')
            &&
            $ticket
            && (
                $ticket->owner_id === $user->id
                ||
                $ticket->responsible_id === $user->id
            )
        ) {
            $ticket->status_id = $statusId;
            $ticket->save();
            $this->notify('success', __('Ticket updated'));
        }
    }
}
