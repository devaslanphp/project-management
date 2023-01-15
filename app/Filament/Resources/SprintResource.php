<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SprintResource\Pages;
use App\Filament\Resources\SprintResource\RelationManagers;
use App\Models\Project;
use App\Models\Sprint;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class SprintResource extends Resource
{
    protected static ?string $model = Sprint::class;

    protected static ?string $navigationIcon = 'heroicon-o-sort-ascending';

    protected static ?int $navigationSort = 3;

    protected static function getNavigationLabel(): string
    {
        return __('Sprints');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
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
                            ->schema([
                                Forms\Components\Select::make('project_id')
                                    ->label(__('Project'))
                                    ->searchable()
                                    ->options(fn() => Project::where(function ($query) {
                                        return $query->where('owner_id', auth()->user()->id)
                                            ->orWhereHas('users', function ($query) {
                                                return $query->where('users.id', auth()->user()->id);
                                            });
                                    })->where('type', 'scrum')->pluck('name', 'id')->toArray())
                                    ->required(),

                                Forms\Components\TextInput::make('name')
                                    ->label(__('Sprint name'))
                                    ->maxLength(255)
                                    ->required(),

                                Forms\Components\DatePicker::make('starts_at')
                                    ->label(__('Sprint start date'))
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, Closure $set) => $set('ends_at', Carbon::parse($state)->addWeek()->subDay()))
                                    ->beforeOrEqual(fn(Closure $get) => $get('ends_at'))
                                    ->required(),

                                Forms\Components\DatePicker::make('ends_at')
                                    ->label(__('Sprint end date'))
                                    ->reactive()
                                    ->afterOrEqual(fn(Closure $get) => $get('starts_at'))
                                    ->required(),

                                Forms\Components\RichEditor::make('description')
                                    ->label(__('Sprint description'))
                                    ->columnSpan(2)
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->label(__('Project'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Sprint name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label(__('Sprint start date'))
                    ->date()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('ends_at')
                    ->label(__('Sprint end date'))
                    ->date()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->label(__('Project'))
                    ->multiple()
                    ->options(fn() => Project::where(fn ($query) => $query->where('owner_id', auth()->user()->id)
                        ->orWhereHas('users', function ($query) {
                            return $query->where('users.id', auth()->user()->id);
                        }))->where('type', 'scrum')->pluck('name', 'id')->toArray()),
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
            RelationManagers\TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSprints::route('/'),
            'create' => Pages\CreateSprint::route('/create'),
            'view' => Pages\ViewSprint::route('/{record}'),
            'edit' => Pages\EditSprint::route('/{record}/edit'),
        ];
    }
}
