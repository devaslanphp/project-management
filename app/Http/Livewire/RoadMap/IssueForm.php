<?php

namespace App\Http\Livewire\RoadMap;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\TicketType;
use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class IssueForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Project|null $project = null;
    public array $epics;
    public array $sprints;

    public function mount()
    {
        $this->initProject($this->project?->id);
        if ($this->project?->status_type === 'custom') {
            $defaultStatus = TicketStatus::where('project_id', $this->project->id)
                ->where('is_default', true)
                ->first()
                ?->id;
        } else {
            $defaultStatus = TicketStatus::whereNull('project_id')
                ->where('is_default', true)
                ->first()
                ?->id;
        }
        $this->form->fill([
            'project_id' => $this->project?->id ?? null,
            'owner_id' => auth()->user()->id,
            'status_id' => $defaultStatus,
            'type_id' => TicketType::where('is_default', true)->first()?->id,
            'priority_id' => TicketPriority::where('is_default', true)->first()?->id
        ]);
    }

    public function render()
    {
        return view('livewire.road-map.issue-form');
    }

    private function initProject($projectId): void
    {
        if ($projectId) {
            $this->project = Project::where('id', $projectId)->first();
        } else {
            $this->project = null;
        }
        $this->epics = $this->project ? $this->project->epics->pluck('name', 'id')->toArray() : [];
        $this->sprints = $this->project ? $this->project->sprints->pluck('name', 'id')->toArray() : [];
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\Grid::make(4)
                        ->schema([
                            Forms\Components\Select::make('project_id')
                                ->label(__('Project'))
                                ->searchable()
                                ->reactive()
                                ->disabled($this->project != null)
                                ->columnSpan(2)
                                ->options(fn() => Project::where('owner_id', auth()->user()->id)
                                    ->orWhereHas('users', function ($query) {
                                        return $query->where('users.id', auth()->user()->id);
                                    })->pluck('name', 'id')->toArray()
                                )
                                ->afterStateUpdated(fn(Closure $get) => $this->initProject($get('project_id')))
                                ->required(),

                            Forms\Components\Select::make('sprint_id')
                                ->label(__('Sprint'))
                                ->searchable()
                                ->reactive()
                                ->visible(fn () => $this->project && $this->project->type === 'scrum')
                                ->columnSpan(2)
                                ->options(fn () => $this->sprints),

                            Forms\Components\Select::make('epic_id')
                                ->label(__('Epic'))
                                ->searchable()
                                ->reactive()
                                ->columnSpan(2)
                                ->required()
                                ->visible(fn () => $this->project && $this->project->type !== 'scrum')
                                ->options(fn () => $this->epics),

                            Forms\Components\TextInput::make('name')
                                ->label(__('Ticket name'))
                                ->required()
                                ->columnSpan(4)
                                ->maxLength(255),
                        ]),

                    Forms\Components\Select::make('owner_id')
                        ->label(__('Ticket owner'))
                        ->searchable()
                        ->options(fn() => User::all()->pluck('name', 'id')->toArray())
                        ->required(),

                    Forms\Components\Select::make('responsible_id')
                        ->label(__('Ticket responsible'))
                        ->searchable()
                        ->options(fn() => User::all()->pluck('name', 'id')->toArray()),

                    Forms\Components\Grid::make()
                        ->columns(3)
                        ->columnSpan(2)
                        ->schema([
                            Forms\Components\Select::make('status_id')
                                ->label(__('Ticket status'))
                                ->searchable()
                                ->options(function ($get) {
                                    if ($this->project?->status_type === 'custom') {
                                        return TicketStatus::where('project_id', $this->project->id)
                                            ->get()
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    } else {
                                        return TicketStatus::whereNull('project_id')
                                            ->get()
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    }
                                })
                                ->required(),

                            Forms\Components\Select::make('type_id')
                                ->label(__('Ticket type'))
                                ->searchable()
                                ->options(fn() => TicketType::all()->pluck('name', 'id')->toArray())
                                ->required(),

                            Forms\Components\Select::make('priority_id')
                                ->label(__('Ticket priority'))
                                ->searchable()
                                ->options(fn() => TicketPriority::all()->pluck('name', 'id')->toArray())
                                ->required(),
                        ]),
                ]),

            Forms\Components\RichEditor::make('content')
                ->label(__('Ticket content'))
                ->required()
                ->columnSpan(2),

            Forms\Components\Grid::make()
                ->columnSpan(2)
                ->columns(12)
                ->schema([
                    Forms\Components\TextInput::make('estimation')
                        ->label(__('Estimation time'))
                        ->numeric()
                        ->columnSpan(4),
                ]),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        Ticket::create($data);
        Filament::notify('success', __('Ticket successfully saved'));
        $this->cancel(true);
    }

    public function cancel($refresh = false): void
    {
        $this->emit('closeTicketDialog', $refresh);
    }
}
