<?php

namespace App\Filament\Resources\TicketPriorityResource\Pages;

use App\Filament\Resources\TicketPriorityResource;
use App\Models\TicketPriority;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicketPriority extends CreateRecord
{
    protected static string $resource = TicketPriorityResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->is_default) {
            TicketPriority::where('id', '<>', $this->record->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
    }
}
