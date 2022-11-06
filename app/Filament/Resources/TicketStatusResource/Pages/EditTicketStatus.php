<?php

namespace App\Filament\Resources\TicketStatusResource\Pages;

use App\Filament\Resources\TicketStatusResource;
use App\Models\TicketStatus;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketStatus extends EditRecord
{
    protected static string $resource = TicketStatusResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->is_default) {
            TicketStatus::where('id', '<>', $this->record->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
        $toUpdate = TicketStatus::where('order', '>=', $this->record->order)
            ->where('id', '<>', $this->record->id)
            ->orderBy('order', 'asc')
            ->get();
        $order = $this->record->order;
        foreach ($toUpdate as $item) {
            if ($item->order == $order || $item->order == ($order + 1)) {
                $item->order = $item->order + 1;
                $item->save();
                $order = $item->order;
            }
        }
    }
}
