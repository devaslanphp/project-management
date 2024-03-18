<?php

namespace App\Filament\Resources\TicketTypeResource\Pages;

use App\Filament\Resources\TicketTypeResource;
use App\Models\TicketType;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketType extends EditRecord
{
    protected static string $resource = TicketTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->is_default) {
            TicketType::where('id', '<>', $this->record->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
    }
}
