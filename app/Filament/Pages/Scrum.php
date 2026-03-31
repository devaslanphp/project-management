<?php

namespace App\Filament\Pages;
use BackedEnum;

use App\Helpers\KanbanScrumHelper;
use App\Models\Project;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Scrum extends Page implements HasForms
{
    use InteractsWithForms, KanbanScrumHelper;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $slug = 'scrum/{project}';

    protected string $view = 'filament.pages.scrum';

    protected static bool $shouldRegisterNavigation = false;

    protected $listeners = [
        'recordUpdated',
        'closeTicketDialog'
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
        if ($this->project->type !== 'scrum') {
            $this->redirect(route('filament.admin.pages.kanban.{project}', ['project' => $project]));
        } elseif (
            $this->project->owner_id != auth()->user()->id
            &&
            !$this->project->users->where('id', auth()->user()->id)->count()
        ) {
            abort(403);
        }
        $this->form->fill();
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('manage-sprints')
                ->button()
                ->visible(fn() => $this->project->currentSprint && auth()->user()->can('update', $this->project))
                ->label(__('Manage sprints'))
                ->color('primary')
                ->url(route('filament.admin.resources.projects.edit', $this->project)),

            Action::make('refresh')
                ->button()
                ->visible(fn() => $this->project->currentSprint)
                ->label(__('Refresh'))
                ->color('gray')
                ->action(function () {
                    $this->getRecords();
                    Notification::make()
                        ->success()
                        ->title(__('Kanban board updated'))
                        ->send();
                }),
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return $this->scrumHeading();
    }

    public function getSubheading(): string|Htmlable|null
    {
        return $this->scrumSubHeading();
    }

    public function getFormSchema(): array
    {
        return $this->formSchema();
    }

}
