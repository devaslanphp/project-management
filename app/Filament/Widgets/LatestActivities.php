<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\TicketComment;
use Closure;
use Filament\Forms\Components\RichEditor;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class LatestActivities extends BaseWidget
{
    protected static ?int $sort = 9;
    protected int|string|array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 3
    ];

    public function mount(): void
    {
        self::$heading = __('Latest tickets activities');
    }

    public static function canView(): bool
    {
        return auth()->user()->can('List tickets');
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableQuery(): Builder
    {
        return TicketActivity::query()
            ->limit(5)
            ->whereHas('ticket', function ($query) {
                return $query->where('owner_id', auth()->user()->id)
                    ->orWhere('responsible_id', auth()->user()->id)
                    ->orWhereHas('project', function ($query) {
                        return $query->where('owner_id', auth()->user()->id)
                            ->orWhereHas('users', function ($query) {
                                return $query->where('users.id', auth()->user()->id);
                            });
                    });
            })
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('ticket')
                ->label(__('Ticket'))
                ->formatStateUsing(function ($record, $state) {
                    return new HtmlString('
                    <div class="flex flex-col gap-1">
                        <span class="text-gray-400 font-medium text-xs">
                            ' . $state->project->name . '
                        </span>
                        <span>
                            <a href="' . route('filament.resources.tickets.share', $state->code)
                        . '" target="_blank" class="text-primary-500 text-sm hover:underline">'
                        . $state->code
                        . '</a>
                            <span class="text-sm text-gray-400">|</span> '
                        . $state->name . '
                        </span>
                        <div class="w-full flex items-center gap-2 text-sm">
                            <span style="color: ' . $record->oldStatus->color . '">'
                                . $record->oldStatus->name
                            . '</span>
                            <span class="text-gray-500">' . __('To') . '</span>
                            <span style="color: ' . $record->newStatus->color . '">
                                ' . $record->newStatus->name . '
                            </span>
                        </div>
                    </div>
                ');
                }),

            Tables\Columns\TextColumn::make('user.name')
                ->label(__('Changed by'))
                ->formatStateUsing(fn($record) => view('components.user-avatar', ['user' => $record->user])),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Performed at'))
                ->dateTime()
        ];
    }
}
