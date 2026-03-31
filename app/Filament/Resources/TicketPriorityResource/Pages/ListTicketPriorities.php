<?php

namespace App\Filament\Resources\TicketPriorityResource\Pages;

use App\Filament\Resources\TicketPriorityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTicketPriorities extends ListRecords
{
    protected static string $resource = TicketPriorityResource::class;

    public function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
