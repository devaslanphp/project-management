<?php

namespace App\Filament\Resources\ProjectStatusResource\Pages;

use App\Filament\Resources\ProjectStatusResource;
use App\Models\ProjectStatus;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectStatus extends EditRecord
{
    protected static string $resource = ProjectStatusResource::class;

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
            ProjectStatus::where('id', '<>', $this->record->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
    }
}
