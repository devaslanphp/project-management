<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Timesheet\ActivitiesReport;
use App\Filament\Widgets\Timesheet\MonthlyReport;
use App\Filament\Widgets\Timesheet\WeeklyReport;
use Filament\Pages\Dashboard;

class TimesheetDashboard extends Dashboard
{
    protected static ?string $slug = 'timesheet-dashboard';

    protected static ?int $navigationSort = 2;

    public function getColumns(): int | array
    {
        return 6;
    }

    public static function getNavigationLabel(): string
    {
        return __('Dashboard');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Timesheet');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('View timesheet dashboard');
    }

    public function getWidgets(): array
    {
        return [
            MonthlyReport::class,
            ActivitiesReport::class,
            WeeklyReport::class
        ];
    }
}
