<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $inverseRelationship = 'projectsAffected';

    public function attach(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('User full name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pivot.role')
                    ->label(__('User role'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => config('system.projects.affectations.roles.list')[$state] ?? $state)
                    ->color(fn (string $state): string => config('system.projects.affectations.roles.colors')[$state] ?? 'gray')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make(),
                Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('role')
                            ->label(__('User role'))
                            ->searchable()
                            ->default(fn () => config('system.projects.affectations.roles.default'))
                            ->options(fn () => config('system.projects.affectations.roles.list'))
                            ->required(),
                    ]),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->modalWidth('xl')
                    ->form(fn (Actions\EditAction $action): array => [
                        Forms\Components\Select::make('role')
                            ->label(__('User role'))
                            ->searchable()
                            ->options(fn () => config('system.projects.affectations.roles.list'))
                            ->required(),
                    ]),
                Actions\DeleteAction::make(),
                Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
                Actions\DetachBulkAction::make(),
            ]);
    }

    public function canCreate(): bool
    {
        return false;
    }

    public function canDelete(Model $record): bool
    {
        return false;
    }

    public function canDeleteAny(): bool
    {
        return false;
    }
}
