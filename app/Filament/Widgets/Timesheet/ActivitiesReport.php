<?php

declare(strict_types=1);

namespace App\Filament\Widgets\Timesheet;

use App\Models\TicketHour;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ActivitiesReport extends ChartWidget
{
    protected int|string|array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 3
    ];

    public ?string $filter = null;

    public function mount(): void
    {
        $this->filter = (string) Carbon::now()->year;
        parent::mount();
    }

    public function getHeading(): string
    {
        return __('Logged time by activity');
    }

    public function getType(): string
    {
        return 'bar';
    }

    public function getFilters(): ?array
    {
        $currentYear = (int) Carbon::now()->year;
        $firstYear = (int) (TicketHour::min('created_at')
            ? Carbon::parse(TicketHour::min('created_at'))->year
            : $currentYear);

        $years = [];
        for ($year = $firstYear; $year <= $currentYear; $year++) {
            $years[(string) $year] = (string) $year;
        }

        return $years;
    }

    public function getData(): array
    {
        $collection = $this->filter(auth()->user(), [
            'year' => $this->filter ?: Carbon::now()->year
        ]);

        $datasets = $this->getDatasets($collection);

        return [
            'datasets' => [
                [
                    'label' => __('Total time logged'),
                    'data' => $datasets['sets'],
                    'backgroundColor' => [
                        'rgba(54, 162, 235, .6)'
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, .8)'
                    ],
                ],
            ],
            'labels' => $datasets['labels'],
        ];
    }

    public function getDatasets(Collection $collection): array
    {
        $datasets = [
            'sets' => [],
            'labels' => []
        ];

        foreach ($collection as $item) {
            $datasets['sets'][] = $item->value;
            $datasets['labels'][] = $item->activity?->name ?? __('No activity');
        }

        return $datasets;
    }

    public function filter(User $user, array $params): Collection
    {
        return TicketHour::with('activity')
            ->select([
                'activity_id',
                DB::raw('SUM(value) as value'),
            ])
            ->whereRaw(
                DB::raw("YEAR(created_at)=" . (is_null($params['year']) ? Carbon::now()->format('Y') : $params['year']))
            )
            ->where('user_id', $user->id)
            ->groupBy('activity_id')
            ->get();
    }
}
