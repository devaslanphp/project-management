<?php

namespace App\Filament\Resources\TicketPriorityResource\Pages;

use App\Filament\Resources\TicketPriorityResource;
use App\Models\TicketPriority;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketPriority extends EditRecord
{
    protected static string $resource = TicketPriorityResource::class;

    public function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function afterSave(): void
    {
        if ($this->record->is_default) {
            TicketPriority::where('id', '<>', $this->record->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
    }
}
