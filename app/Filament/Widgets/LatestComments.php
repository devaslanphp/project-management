<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketComment;
use Closure;
use Filament\Forms\Components\RichEditor;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class LatestComments extends BaseWidget
{
    protected static ?int $sort = 8;
    protected int|string|array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 3
    ];

    public function mount(): void
    {
        self::$heading = __('Latest tickets comments');
    }

    public static function canView(): bool
    {
        return auth()->user()->can('List tickets');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TicketComment::query()
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
                    ->latest()
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('ticket')
                    ->label(__('Ticket'))
                    ->formatStateUsing(function ($state) {
                        return new HtmlString('
                        <div class="flex flex-col gap-1">
                            <span class="text-gray-400 font-medium text-xs">
                                ' . $state->project->name . '
                            </span>
                            <span>
                                <a href="' . route('filament.admin.resources.tickets.share', $state->code)
                            . '" target="_blank" class="text-primary-500 text-sm hover:underline">'
                            . $state->code
                            . '</a>
                                <span class="text-sm text-gray-400">|</span> '
                            . $state->name . '
                            </span>
                        </div>
                    ');
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Owner'))
                    ->formatStateUsing(fn($record) => view('components.user-avatar', ['user' => $record->user])),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Commented at'))
                    ->dateTime()
            ])
            ->actions([
                Actions\Action::make('view')
                    ->label(__('View'))
                    ->icon('heroicon-s-eye')
                    ->color('gray')
                    ->modalHeading(__('Comment details'))
                    ->modalSubmitActionLabel(__('View ticket'))
                    ->form([
                        RichEditor::make('content')
                            ->label(__('Content'))
                            ->default(fn($record) => $record->content)
                            ->disabled()
                    ])
                    ->action(
                        fn($record) =>
                            redirect()->to(route('filament.admin.resources.tickets.share', $record->ticket->code))
                    )
            ]);
    }
}
