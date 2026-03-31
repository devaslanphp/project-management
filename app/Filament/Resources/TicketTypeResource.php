<?php

namespace App\Filament\Resources;
use BackedEnum;

use App\Filament\Resources\TicketTypeResource\Pages;
use App\Filament\Resources\TicketTypeResource\RelationManagers;
use App\Models\TicketType;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Tables;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Guava\FilamentIconPicker\Tables\IconColumn;

class TicketTypeResource extends Resource
{
    protected static ?string $model = TicketType::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Ticket types');
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
                                    ->label(__('Type name'))
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\ColorPicker::make('color')
                                    ->label(__('Type color'))
                                    ->required(),

                                IconPicker::make('icon')
                                    ->label(__('Type icon'))
                                    ->required(),

                                Forms\Components\Checkbox::make('is_default')
                                    ->label(__('Default type'))
                                    ->helperText(
                                        __('If checked, this type will be automatically affected to new tickets')
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
                    ->label(__('Type color'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Type name'))
                    ->sortable()
                    ->searchable(),

                IconColumn::make('icon')
                    ->label(__('Type icon'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_default')
                    ->label(__('Default type'))
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
            'index' => Pages\ListTicketTypes::route('/'),
            'create' => Pages\CreateTicketType::route('/create'),
            'view' => Pages\ViewTicketType::route('/{record}'),
            'edit' => Pages\EditTicketType::route('/{record}/edit'),
        ];
    }
}
