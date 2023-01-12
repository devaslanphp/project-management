<?php

namespace App\Filament\Resources\TimesheetResource\Pages;

use App\Filament\Resources\TimesheetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimesheet extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }
}
