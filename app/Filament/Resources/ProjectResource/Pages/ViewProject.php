<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    public function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kanban')
                ->label(
                    fn ()
                    => ($this->record->type === 'scrum' ? __('Scrum board') : __('Kanban board'))
                )
                ->icon('heroicon-o-view-columns')
                ->color('gray')
                ->url(function () {
                    if ($this->record->type === 'scrum') {
                        return route('filament.admin.pages.scrum.{project}', ['project' => $this->record->id]);
                    } else {
                        return route('filament.admin.pages.kanban.{project}', ['project' => $this->record->id]);
                    }
                }),

            Actions\EditAction::make(),
        ];
    }
}
