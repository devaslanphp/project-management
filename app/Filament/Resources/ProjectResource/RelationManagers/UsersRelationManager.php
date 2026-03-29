<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
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

                Tables\Columns\BadgeColumn::make('pivot.role')
                    ->label(__('User role'))
                    ->enum(config('system.projects.affectations.roles.list'))
                    ->colors(config('system.projects.affectations.roles.colors'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
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
                Tables\Actions\EditAction::make()
                    ->modalWidth('xl')
                    ->form(fn (Tables\Actions\EditAction $action): array => [
                        Forms\Components\Select::make('role')
                            ->label(__('User role'))
                            ->searchable()
                            ->options(fn () => config('system.projects.affectations.roles.list'))
                            ->required(),
                    ]),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }

    protected function canCreate(): bool
    {
        return false;
    }

    protected function canDelete(Model $record): bool
    {
        return false;
    }

    protected function canDeleteAny(): bool
    {
        return false;
    }
}
