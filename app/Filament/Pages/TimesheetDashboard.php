<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Timesheet\MonthlyReport;
use Filament\Pages\Page;

class TimesheetDashboard extends Page
{
    protected static ?string $slug = 'timesheet-dashboard';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament::pages.dashboard';

    protected function getColumns(): int | array
    {
        return 6;
    }

    protected static function getNavigationLabel(): string
    {
        return __('Dashboard');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Timesheet');
    }

    protected function getWidgets(): array
    {
        return [
            MonthlyReport::class
        ];
    }
}
