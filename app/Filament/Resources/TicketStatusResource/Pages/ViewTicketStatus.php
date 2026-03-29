<?php

namespace App\Filament\Resources\TicketStatusResource\Pages;

use App\Filament\Resources\TicketStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTicketStatus extends ViewRecord
{
    protected static string $resource = TicketStatusResource::class;

    public function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
