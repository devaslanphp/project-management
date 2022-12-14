<?php

namespace App\Filament\Pages;

use App\Models\Project;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class RoadMap extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.road-map';

    protected static ?string $slug = 'road-map';

    protected static ?int $navigationSort = 4;

    protected static function getNavigationLabel(): string
    {
        return __('Road Map');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('project')
                ->placeholder(__('Project'))
                ->disableLabel()
                ->searchable()
                ->extraAttributes([
                    'class' => 'min-w-[16rem]'
                ])
                ->required()
                ->options(function () {
                    return Project::where(function ($query) {
                        return $query->where('owner_id', auth()->user()->id)
                            ->orWhereHas('users', function ($query) {
                                return $query->where('users.id', auth()->user()->id);
                            });
                    })->get()->pluck('name', 'id')->toArray();
                })
        ];
    }

    public function filter(): void
    {
        $data = $this->form->getState();
        $project = $data['project'];
        $this->dispatchBrowserEvent('projectChanged', compact('project'));
    }
}
