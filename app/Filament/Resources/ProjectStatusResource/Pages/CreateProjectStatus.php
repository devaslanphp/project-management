<?php

namespace App\Filament\Resources\ProjectStatusResource\Pages;

use App\Filament\Resources\ProjectStatusResource;
use App\Models\ProjectStatus;
use Filament\Resources\Pages\CreateRecord;

class CreateProjectStatus extends CreateRecord
{
    protected static string $resource = ProjectStatusResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->is_default) {
            ProjectStatus::where('id', '<>', $this->record->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
    }
}
