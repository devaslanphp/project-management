<?php

namespace App\Filament\Pages;

use App\Models\Project;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Board extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-view-boards';

    protected static string $view = 'filament.pages.board';

    protected static ?string $slug = 'board';

    protected static ?int $navigationSort = 4;

    protected function getSubheading(): string|Htmlable|null
    {
        return __("In this section you can choose one of your projects to show it's Scrum or Kanban board");
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    protected static function getNavigationLabel(): string
    {
        return __('Board');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    Grid::make()
                        ->columns(1)
                        ->schema([
                            Select::make('project')
                                ->label(__('Project'))
                                ->required()
                                ->searchable()
                                ->reactive()
                                ->afterStateUpdated(fn () => $this->search())
                                ->helperText(__("Choose a project to show it's board"))
                                ->options(fn() => Project::where('owner_id', auth()->user()->id)
                                    ->orWhereHas('users', function ($query) {
                                        return $query->where('users.id', auth()->user()->id);
                                    })->pluck('name', 'id')->toArray()),
                        ]),
                ]),
        ];
    }

    public function search(): void
    {
        $data = $this->form->getState();
        $project = Project::find($data['project']);
        if ($project->type === "scrum") {
            $this->redirect(route('filament.pages.scrum/{project}', ['project' => $project]));
        } else {
            $this->redirect(route('filament.pages.kanban/{project}', ['project' => $project]));
        }
    }
}
