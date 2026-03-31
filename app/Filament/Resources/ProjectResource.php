<?php

namespace App\Filament\Resources;
use BackedEnum;

use App\Exports\ProjectHoursExport;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use App\Models\ProjectFavorite;
use App\Models\ProjectStatus;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Tables;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Projects');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Schemas\Components\Section::make()
                    ->schema([
                        Schemas\Components\Grid::make()
                            ->columns(3)
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                                    ->label(__('Cover image'))
                                    ->image()
                                    ->helperText(
                                        __('If not selected, an image will be generated based on the project name')
                                    )
                                    ->columnSpan(1),

                                Schemas\Components\Grid::make()
                                    ->columnSpan(2)
                                    ->schema([
                                        Schemas\Components\Grid::make()
                                            ->columnSpan(2)
                                            ->columns(12)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label(__('Project name'))
                                                    ->required()
                                                    ->columnSpan(10)
                                                    ->maxLength(255),

                                                Forms\Components\TextInput::make('ticket_prefix')
                                                    ->label(__('Ticket prefix'))
                                                    ->maxLength(3)
                                                    ->columnSpan(2)
                                                    ->unique(Project::class, column: 'ticket_prefix', ignoreRecord: true)
                                                    ->disabled(
                                                        fn($record) => $record && $record->tickets()->count() != 0
                                                    )
                                                    ->required()
                                            ]),

                                        Forms\Components\Select::make('owner_id')
                                            ->label(__('Project owner'))
                                            ->searchable()
                                            ->options(fn() => User::all()->pluck('name', 'id')->toArray())
                                            ->default(fn() => auth()->user()->id)
                                            ->required(),

                                        Forms\Components\Select::make('status_id')
                                            ->label(__('Project status'))
                                            ->searchable()
                                            ->options(fn() => ProjectStatus::all()->pluck('name', 'id')->toArray())
                                            ->default(fn() => ProjectStatus::where('is_default', true)->first()?->id)
                                            ->required(),
                                    ]),

                                Forms\Components\RichEditor::make('description')
                                    ->label(__('Project description'))
                                    ->columnSpan(3),

                                Forms\Components\Select::make('type')
                                    ->label(__('Project type'))
                                    ->searchable()
                                    ->options([
                                        'kanban' => __('Kanban'),
                                        'scrum' => __('Scrum')
                                    ])
                                    ->live()
                                    ->default(fn() => 'kanban')
                                    ->helperText(function ($state) {
                                        if ($state === 'kanban') {
                                            return __('Display and move your project forward with issues on a powerful board.');
                                        } elseif ($state === 'scrum') {
                                            return __('Achieve your project goals with a board, backlog, and roadmap.');
                                        }
                                        return '';
                                    })
                                    ->required(),

                                Forms\Components\Select::make('status_type')
                                    ->label(__('Statuses configuration'))
                                    ->helperText(
                                        __('If custom type selected, you need to configure project specific statuses')
                                    )
                                    ->searchable()
                                    ->options([
                                        'default' => __('Default'),
                                        'custom' => __('Custom configuration')
                                    ])
                                    ->default(fn() => 'default')
                                    ->disabled(fn($record) => $record && $record->tickets()->count())
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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

                Tables\Columns\TextColumn::make('users.name')
                    ->label(__('Affected users'))
                    ->badge()
                    ->limit(2),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'kanban' => __('Kanban'),
                        'scrum' => __('Scrum'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'kanban' => 'gray',
                        'scrum' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('owner_id')
                    ->label(__('Owner'))
                    ->multiple()
                    ->options(fn() => User::all()->pluck('name', 'id')->toArray()),

                Tables\Filters\SelectFilter::make('status_id')
                    ->label(__('Status'))
                    ->multiple()
                    ->options(fn() => ProjectStatus::all()->pluck('name', 'id')->toArray()),
            ])
            ->actions([

                Actions\Action::make('favorite')
                    ->label('')
                    ->icon('heroicon-o-star')
                    ->color(fn($record) => auth()->user()->favoriteProjects()
                        ->where('projects.id', $record->id)->count() ? 'success' : 'default')
                    ->action(function ($record) {
                        $projectId = $record->id;
                        $projectFavorite = ProjectFavorite::where('project_id', $projectId)
                            ->where('user_id', auth()->user()->id)
                            ->first();
                        if ($projectFavorite) {
                            $projectFavorite->delete();
                        } else {
                            ProjectFavorite::create([
                                'project_id' => $projectId,
                                'user_id' => auth()->user()->id
                            ]);
                        }
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title(__('Project updated'))
                            ->send();
                    }),

                Actions\ViewAction::make(),
                Actions\EditAction::make(),

                Actions\ActionGroup::make([
                    Actions\Action::make('exportLogHours')
                        ->label(__('Export hours'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->action(fn($record) => Excel::download(
                            new ProjectHoursExport($record),
                            'time_' . Str::slug($record->name) . '.csv',
                            \Maatwebsite\Excel\Excel::CSV,
                            ['Content-Type' => 'text/csv']
                        )),

                    Actions\Action::make('kanban')
                        ->label(
                            fn ($record)
                                => ($record->type === 'scrum' ? __('Scrum board') : __('Kanban board'))
                        )
                        ->icon('heroicon-o-view-columns')
                        ->color('gray')
                        ->url(function ($record) {
                            if ($record->type === 'scrum') {
                                return route('filament.admin.pages.scrum.{project}', ['project' => $record->id]);
                            } else {
                                return route('filament.admin.pages.kanban.{project}', ['project' => $record->id]);
                            }
                        }),
                ])->color('gray'),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SprintsRelationManager::class,
            RelationManagers\UsersRelationManager::class,
            RelationManagers\StatusesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
