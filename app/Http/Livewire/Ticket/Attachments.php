<?php

namespace App\Http\Livewire\Ticket;

use App\Models\Ticket;
use Filament\Facades\Filament;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Attachments extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public Ticket $ticket;

    protected $listeners = [
        'filesUploaded'
    ];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.ticket.attachments');
    }

    protected function getFormModel(): Model|string|null
    {
        return $this->ticket;
    }

    protected function getFormSchema(): array
    {
        return [
            SpatieMediaLibraryFileUpload::make('attachments')
                ->label(__('Attachments'))
                ->hint(__('Important: If a file has the same name, it will be replaced'))
                ->helperText(__('Here you can attach all files needed for this ticket'))
                ->multiple()
                ->disablePreview()
        ];
    }

    public function perform(): void
    {
        $this->form->getState();
        $this->form->fill();
        $this->emit('filesUploaded');
        Filament::notify('success', __('Ticket attachments saved'));
    }

    public function filesUploaded(): void
    {
        $this->ticket->refresh();
    }

    protected function getTableQuery(): Builder
    {
        return $this->ticket->media()->getQuery();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label(__('Name'))
                ->sortable()
                ->searchable(),

            TextColumn::make('human_readable_size')
                ->label(__('Size'))
                ->sortable()
                ->searchable(),

            TextColumn::make('mime_type')
                ->label(__('Mime type'))
                ->sortable()
                ->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            DeleteAction::make()
                ->action(function ($record) {
                    $record->delete();
                    Filament::notify('success', __('Ticket attachment deleted'));
                })
        ];
    }
}
