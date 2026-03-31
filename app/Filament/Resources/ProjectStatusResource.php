<?php

namespace App\Filament\Resources;
use BackedEnum;

use App\Filament\Resources\ProjectStatusResource\Pages;
use App\Filament\Resources\ProjectStatusResource\RelationManagers;
use App\Models\ProjectStatus;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Tables;

class ProjectStatusResource extends Resource
{
    protected static ?string $model = ProjectStatus::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Project statuses');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Referential');
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Schemas\Components\Section::make()
                    ->schema([
                        Schemas\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('Status name'))
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\ColorPicker::make('color')
                                    ->label(__('Status color'))
                                    ->required(),

                                Forms\Components\Checkbox::make('is_default')
                                    ->label(__('Default status'))
                                    ->helperText(
                                        __('If checked, this status will be automatically affected to new projects')
                                    ),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('Status color'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Status name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_default')
                    ->label(__('Default status'))
                    ->boolean()
                    ->sortable(),

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
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectStatuses::route('/'),
            'create' => Pages\CreateProjectStatus::route('/create'),
            'view' => Pages\ViewProjectStatus::route('/{record}'),
            'edit' => Pages\EditProjectStatus::route('/{record}/edit'),
        ];
    }
}
