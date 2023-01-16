<?php

namespace App\Filament\Pages;

use App\Helpers\KanbanScrumHelper;
use App\Models\Project;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Scrum extends Page implements HasForms
{
    use InteractsWithForms, KanbanScrumHelper;

    protected static ?string $navigationIcon = 'heroicon-o-view-boards';

    protected static ?string $slug = 'scrum/{project}';

    protected static string $view = 'filament.pages.scrum';

    protected static bool $shouldRegisterNavigation = false;

    protected $listeners = [
        'recordUpdated',
        'closeTicketDialog'
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
        if ($this->project->type !== 'scrum') {
            $this->redirect(route('filament.pages.kanban/{project}', ['project' => $project]));
        } elseif (
            $this->project->owner_id != auth()->user()->id
            &&
            !$this->project->users->where('id', auth()->user()->id)->count()
        ) {
            abort(403);
        }
        $this->form->fill();
    }

    protected function getActions(): array
    {
        return [
            Action::make('manage-sprints')
                ->button()
                ->visible(fn() => $this->project->currentSprint && auth()->user()->can('update', $this->project))
                ->label(__('Manage sprints'))
                ->color('primary')
                ->url(route('filament.resources.projects.edit', $this->project)),

            Action::make('refresh')
                ->button()
                ->visible(fn() => $this->project->currentSprint)
                ->label(__('Refresh'))
                ->color('secondary')
                ->action(function () {
                    $this->getRecords();
                    Filament::notify('success', __('Kanban board updated'));
                }),
        ];
    }

    protected function getHeading(): string|Htmlable
    {
        return $this->scrumHeading();
    }

    protected function getSubheading(): string|Htmlable|null
    {
        return $this->scrumSubHeading();
    }

    protected function getFormSchema(): array
    {
        return $this->formSchema();
    }

}
