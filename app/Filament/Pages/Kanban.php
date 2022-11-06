<?php

namespace App\Filament\Pages;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Filament\Facades\Filament;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;

class Kanban extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-view-boards';

    protected static ?string $slug = 'kanban';

    protected static string $view = 'filament.pages.kanban';

    protected static ?int $navigationSort = 3;

    public bool $sortable = true;

    public Project|null $project = null;

    protected $listeners = [
        'recordUpdated'
    ];

    public function mount()
    {
        if (request()->has('project')) {
            $this->project = Project::find(request()->get('project'));
            if (!$this->project->users->where('id', auth()->user()->id)->count()) {
                abort(403);
            }
        }
    }

    protected function getActions(): array
    {
        return [
            Action::make('refresh')
                ->button()
                ->label(__('Refresh'))
                ->color('secondary')
                ->action(function () {
                    $this->getRecords();
                    Filament::notify('success', __('Kanban board updated'));
                })
        ];
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    protected function getHeading(): string|Htmlable
    {
        $heading = __('Kanban');
        if ($this->project) {
            $heading .= ' - ' . $this->project->name;
        }
        return $heading;
    }

    public function getStatuses(): Collection
    {
        return TicketStatus::orderBy('order')
            ->get()
            ->map(function ($item) {
                $query = Ticket::query();
                if ($this->project) {
                    $query->where('project_id', $this->project->id);
                }
                $query->where('status_id', $item->id);
                return [
                    'id' => $item->id,
                    'title' => $item->name,
                    'color' => $item->color,
                    'size' => $query->count(),
                    'add_ticket' => $item->is_default && auth()->user()->can('Create ticket')
                ];
            });
    }

    public function getRecords(): Collection
    {
        $query = Ticket::query();
        $query->with(['project', 'owner', 'responsible', 'status', 'type', 'priority']);
        if ($this->project) {
            $query->where('project_id', $this->project->id);
        }
        $query->where(function ($query) {
            return $query->where('owner_id', auth()->user()->id)
                ->orWhere('responsible_id', auth()->user()->id)
                ->orWhereHas('project', function ($query) {
                    return $query->where('owner_id', auth()->user()->id)
                        ->orWhereHas('users', function ($query) {
                            return $query->where('users.id', auth()->user()->id);
                        });
                });
        });
        return $query->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'code' => $item->code,
                'title' => $item->name,
                'owner' => $item->owner,
                'type' => $item->type,
                'responsible' => $item->responsible,
                'project' => $item->project,
                'status' => $item->status->id,
                'priority' => $item->priority
            ]);
    }

    public function recordUpdated(int $record, int $newIndex, int $newStatus): void
    {
        $ticket = Ticket::find($record);
        if ($ticket) {
            $ticket->order = $newIndex;
            $ticket->status_id = $newStatus;
            $ticket->save();
            Filament::notify('success', __('Ticket updated'));
        }
    }

    public function isMultiProject(): bool
    {
        return $this->project === null;
    }

}
