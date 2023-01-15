<?php

namespace App\Http\Livewire\RoadMap;

use App\Models\Epic;
use App\Models\Project;
use App\Models\Ticket;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class EpicForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Epic $epic;
    public array $epics = [];

    public function mount()
    {
        $query = Epic::query();
        $query->where('project_id', $this->epic->project_id);
        if ($this->epic->id) {
            $query->where('id', '<>', $this->epic->id);
        }
        $this->epics = $query->get()->pluck('name', 'id')->toArray();
        $this->form->fill($this->epic->toArray());
    }

    public function render()
    {
        return view('livewire.road-map.epic-form');
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Select::make('project_id')
                        ->label(__('Project'))
                        ->disabled()
                        ->options(Project::all()->pluck('name', 'id')),

                    Select::make('parent_id')
                        ->label(__('Parent epic'))
                        ->searchable()
                        ->options($this->epics),
                ]),

            TextInput::make('name')
                ->label(__('Epic name'))
                ->required()
                ->maxLength(255),

            Grid::make()
                ->schema([
                    DatePicker::make('starts_at')
                        ->label(__('Starts at'))
                        ->required(),

                    DatePicker::make('ends_at')
                        ->label(__('Ends at'))
                        ->required(),
                ]),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $this->epic->project_id = $data['project_id'];
        $this->epic->parent_id = $data['parent_id'];
        $this->epic->name = $data['name'];
        $this->epic->starts_at = $data['starts_at'];
        $this->epic->ends_at = $data['ends_at'];
        $this->epic->save();
        Filament::notify('success', __('Epic successfully saved'));
        $this->cancel(true);
    }

    public function cancel($refresh = false): void
    {
        $this->emit('closeEpicDialog', $refresh);
    }

    public function delete(): void
    {
        $this->epic->tickets->each(function (Ticket $ticket) {
            $ticket->epic_id = null;
            $ticket->save();
        });
        $this->epic->delete();
        $this->emit('closeEpicDialog', true);
    }
}
