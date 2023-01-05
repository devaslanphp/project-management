<?php

namespace App\Http\Livewire\Ticket;

use App\Models\Ticket;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Attachments extends Component implements HasForms
{
    use InteractsWithForms;

    public Ticket $ticket;

    public function mount(): void
    {
        $this->form->fill([
            'attachments' => $this->ticket->attachments
        ]);
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
            FileUpload::make('attachments')
                ->label(__('Attachments'))
                ->hint(__('Important: If a file has the same name, it will be replaced'))
                ->helperText(__('Here you can attach all files needed for this ticket'))
                ->multiple()
                ->disablePreview()
                ->enableReordering()
                ->enableOpen()
                ->preserveFilenames()
                ->enableDownload()
                ->directory(fn() => 'tickets/' . $this->ticket->code)
        ];
    }

    public function perform(): void
    {
        $data = $this->form->getState();
        $this->ticket->attachments = $data['attachments'];
        $this->ticket->save();
        $this->ticket->refresh();
        Filament::notify('success', __('Ticket attachments saved'));
    }
}
