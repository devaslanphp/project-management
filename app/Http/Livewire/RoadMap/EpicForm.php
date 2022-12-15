<?php

namespace App\Http\Livewire\RoadMap;

use App\Models\Epic;
use App\Models\Project;
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

    public function mount()
    {
        $this->form->fill($this->epic->toArray());
    }

    public function render()
    {
        return view('livewire.road-map.epic-form');
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('project_id')
                ->label(__('Project'))
                ->disabled()
                ->options(Project::all()->pluck('name', 'id')),

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
        $this->epic->name = $data['name'];
        $this->epic->starts_at = $data['starts_at'];
        $this->epic->ends_at = $data['ends_at'];
        $this->epic->save();
        Filament::notify('success', __('Epic successfully saved'));
        $this->cancel(true);
    }

    public function cancel($refresh = false): void
    {
        $this->emit('cancelCreateEpic', $refresh);
    }
}
