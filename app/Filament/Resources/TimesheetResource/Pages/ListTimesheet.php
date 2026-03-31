<?php

namespace App\Filament\Resources\TimesheetResource\Pages;

use App\Filament\Resources\TimesheetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimesheet extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    public function getHeaderActions(): array
    {
        return [
        ];
    }
}
