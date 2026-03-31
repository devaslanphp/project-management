<?php

namespace App\Filament\Resources\ProjectStatusResource\Pages;

use App\Filament\Resources\ProjectStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectStatus extends ViewRecord
{
    protected static string $resource = ProjectStatusResource::class;

    public function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
