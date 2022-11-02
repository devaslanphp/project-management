<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\HtmlString;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive';

    protected static ?int $navigationSort = 1;

    protected static function getNavigationLabel(): string
    {
        return __('Projects');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns(3)
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                                    ->label(__('Cover image'))
                                    ->image()
                                    ->helperText(
                                        __('If not selected, an image will be generated based on the project name')
                                    )
                                    ->columnSpan(1),

                                Forms\Components\Grid::make()
                                    ->columnSpan(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('Project name'))
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(2),

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
                    ->formatStateUsing(fn ($state) => new HtmlString('
                        <div style=\'background-image: url("' . $state . '")\'
                             class="w-8 h-8 rounded bg-cover bg-center bg-no-repeat bg-gray-50"></div>
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class,
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
