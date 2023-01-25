<?php

declare(strict_types=1);

namespace App\Filament\Widgets\Timesheet;

use App\Models\TicketHour;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\BarChartWidget;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WeeklyReport extends BarChartWidget
{
    protected int|string|array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 3
    ];

    protected function getData(): array
    {
        $now = Carbon::now();

        $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');

        $collection = $this->filter(auth()->user(), [
            'year' => null,
            'weekStartDate' => $weekStartDate,
            'weekEndDate' => $weekEndDate
        ]);

        $dates = $this->buildDatesRange($weekStartDate, $weekEndDate);

        $datasets = $this->buildRapport($collection, $dates);

        return [
            'datasets' => [
                [
                    'label' => __('Weekly time logged'),
                    'data' => $datasets,
                    'backgroundColor' => [
                        'rgba(54, 162, 235, .6)'
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, .8)'
                    ],
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function buildRapport(Collection $collection, array $dates): array
    {
        $template = $this->createReportTemplate($dates);
        foreach ($collection as $item) {
            $template[$item->day]['value'] =  $item->value;
        }
        return collect($template)->pluck('value')->toArray();
    }

    protected function filter(User $user, array $params)
    {
        return TicketHour::select([
            DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as day"),
            DB::raw('SUM(value) as value'),
        ])
            ->whereBetween('created_at', [$params['weekStartDate'], $params['weekEndDate']])
            ->whereRaw(
                DB::raw("YEAR(created_at)=" . (is_null($params['year']) ? Carbon::now()->format('Y') : $params['year']))
            )
            ->where('user_id', $user->id)
            ->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))
            ->get();
    }

    protected function buildDatesRange($weekStartDate, $weekEndDate): array
    {
        $period = CarbonPeriod::create($weekStartDate, $weekEndDate);

        $dates = [];
        foreach ($period as $item) {
            $dates[] = $item->format('Y-m-d');
        }

        return $dates;
    }

    protected function createReportTemplate(array $dates): array
    {
        $template = [];
        foreach ($dates as $date) {
            $template[$date]['value'] = 0;
        }
        return $template;
    }
}
