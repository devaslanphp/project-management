<?php

namespace App\Filament\Resources\TicketTypeResource\Pages;

use App\Filament\Resources\TicketTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTicketType extends ViewRecord
{
    protected static string $resource = TicketTypeResource::class;

    public function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
