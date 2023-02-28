<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TimesheetExport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $slug = 'timesheet-export';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.timesheet-export';

    protected static function getNavigationGroup(): ?string
    {
        return __('Timesheet');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('start_date')
                            ->required()
                            ->reactive()
                            ->label('Star date'),
                        DatePicker::make('end_date')
                            ->required()
                            ->reactive()
                            ->label('End date')
                    ])
            ])
        ];
    }

    public function create(): BinaryFileResponse
    {
        $data = $this->form->getState();

        return Excel::download(
            new \App\Exports\TimesheetExport($data),
            'time_' . time() . '.csv',
            \Maatwebsite\Excel\Excel::CSV,
            ['Content-Type' => 'text/csv']
        );
    }
}
