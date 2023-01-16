<?php

declare(strict_types=1);

namespace App\Filament\Widgets\Timesheet;

use App\Models\TicketHour;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyReport extends BarChartWidget
{
    protected function getHeading(): string
    {
        return __('Logged time monthly');
    }

    protected function getData(): array
    {
        $months = [
            1 => ['January', 0],
            2 => ['February', 0],
            3 => ['March', 0],
            4 => ['April', 0],
            5 => ['May', 0],
            6 => ['June', 0],
            7 => ['July', 0],
            8 => ['August', 0],
            9 => ['September', 0],
            10 => ['October', 0],
            11 => ['November', 0],
            12 => ['December', 0]
        ];


        $data = $this->filter(auth()->user());

        foreach ($data as $value) {
            if (isset($months[(int)$value->month])) {
                $months[(int)$value->month][1] = (float)$value->value;
            }
        }

        $datasets = [];
        $labels = [];
        foreach ($months as $month) {
            $datasets[] = $month[1];
            $labels[] = $month[0];
        }

        return [
            'datasets' => [
                [
                    'label' => __('Total time logged'),
                    'data' => $datasets,
                    'backgroundColor' => [
                        'rgba(54, 162, 235, .6)'
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, .8)'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected int|string|array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 3
    ];

    protected function filter(User $user)
    {
        return TicketHour::select([
            DB::raw("DATE_FORMAT (created_at, '%m') as month"),
            DB::raw('SUM(value) as value'),
        ])
            ->where('user_id', $user->id)
            ->whereRaw(DB::raw("YEAR(created_at)=") . Carbon::now()->format('Y'))
            ->groupBy(DB::raw("DATE_FORMAT (created_at, '%m')"))
            ->get();
    }
}
