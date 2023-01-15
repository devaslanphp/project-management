<?php

namespace App\Filament\Resources\SprintResource\RelationManagers;

use App\Filament\Resources\TicketResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    protected static ?string $recordTitleAttribute = 'name';

    public static function table(Table $table): Table
    {
        return $table
            ->columns(TicketResource::tableColumns(false))
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(
                        fn (Builder $query, TicketsRelationManager $livewire)
                            => $query->where('project_id', $livewire->getOwnerRecord()->project_id)
                    ),
            ])
            ->actions([
                Tables\Actions\DissociateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DissociateBulkAction::make(),
            ]);
    }
}
