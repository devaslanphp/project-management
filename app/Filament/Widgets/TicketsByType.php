<?php

namespace App\Filament\Widgets;

use App\Models\TicketType;
use Filament\Widgets\ChartWidget;

class TicketsByType extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Chart';
    protected ?string $maxHeight = '300px';
    protected int|string|array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 3
    ];

    public static function canView(): bool
    {
        return auth()->user()->can('List tickets');
    }

    public function getHeading(): string
    {
        return __('Tickets by types');
    }

    public function getType(): string
    {
        return 'doughnut';
    }

    public function getData(): array
    {
        $data = TicketType::withCount('tickets')->get();
        return [
            'datasets' => [
                [
                    'label' => __('Tickets by types'),
                    'data' => $data->pluck('tickets_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, .6)',
                        'rgba(54, 162, 235, .6)',
                        'rgba(255, 205, 86, .6)'
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, .8)',
                        'rgba(54, 162, 235, .8)',
                        'rgba(255, 205, 86, .8)'
                    ],
                    'hoverOffset' => 4
                ]
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }
}
