<?php

namespace App\Livewire\Ticket;

use App\Models\Ticket;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Attachments extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

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
                ->previewable(false)
        ];
    }

    public function perform(): void
    {
        $this->form->getState();
        $this->form->fill();
        $this->dispatch('filesUploaded');
        Notification::make()
            ->success()
            ->title(__('Ticket attachments saved'))
            ->send();
    }

    public function filesUploaded(): void
    {
        $this->ticket->refresh();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->ticket->media()->getQuery())
            ->columns([
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
            ])
            ->actions([
                DeleteAction::make()
                    ->action(function ($record) {
                        $record->delete();
                        Notification::make()
                            ->success()
                            ->title(__('Ticket attachment deleted'))
                            ->send();
                    })
            ]);
    }
}
