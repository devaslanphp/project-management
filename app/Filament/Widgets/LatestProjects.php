<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class LatestProjects extends BaseWidget
{
    public function mount(): void
    {
        self::$heading = __('Latest projects');
    }

    public static function canView(): bool
    {
        return auth()->user()->can('List projects');
    }

    protected function getTableQuery(): Builder
    {
        return Project::query()
            ->where(function ($query) {
                return $query->where('owner_id', auth()->user()->id)
                    ->orWhereHas('users', function ($query) {
                        return $query->where('users.id', auth()->user()->id);
                    });
            })
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('cover')
                ->label(__('Cover image'))
                ->formatStateUsing(fn($state) => new HtmlString('
                            <div style=\'background-image: url("' . $state . '")\'
                                 class="w-8 h-8 bg-cover bg-center bg-no-repeat"></div>
                        ')),

            Tables\Columns\TextColumn::make('name')
                ->label(__('Project name'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('owner.name')
                ->label(__('Project owner'))
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('status.name')
                ->label(__('Project status'))
                ->formatStateUsing(fn($record) => new HtmlString('
                            <div class="flex items-center gap-2">
                                <span class="filament-tables-color-column relative flex h-6 w-6 rounded-md"
                                    style="background-color: ' . $record->status->color . '"></span>
                                <span>' . $record->status->name . '</span>
                            </div>
                        '))
                ->sortable()
                ->searchable(),

            Tables\Columns\TagsColumn::make('users.name')
                ->label(__('Affected users'))
                ->limit(2),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->label(__('View'))
                ->icon('heroicon-s-eye')
                ->color('secondary')
                ->link()
                ->url(fn ($record) => route('filament.resources.projects.view', $record), true)
        ];
    }
}
