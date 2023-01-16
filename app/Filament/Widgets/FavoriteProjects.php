<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\HtmlString;

class FavoriteProjects extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = [
        'sm' => 1,
        'md' => 6,
        'lg' => 6
    ];

    protected function getColumns(): int
    {
        return 4;
    }

    public static function canView(): bool
    {
        return auth()->user()->can('List projects');
    }

    protected function getCards(): array
    {
        $favoriteProjects = auth()->user()->favoriteProjects;
        $cards = [];
        foreach ($favoriteProjects as $project) {
            $ticketsCount = $project->tickets()->count();
            $contributorsCount = $project->contributors->count();
            $cards[] = Card::make('', new HtmlString('
                    <div class="flex items-center gap-2 -mt-2 text-lg">
                        <div style=\'background-image: url("' . $project->cover . '")\'
                             class="w-8 h-8 bg-cover bg-center bg-no-repeat"></div>
                        <span>' . $project->name . '</span>
                    </div>
                '))
                ->color('success')
                ->extraAttributes([
                    'class' => 'hover:shadow-lg'
                ])
                ->description(new HtmlString('
                        <div class="w-full flex items-center gap-2 mt-2 text-gray-500 font-normal">'
                            . $ticketsCount
                            . ' '
                            . __($ticketsCount > 1 ? 'Tickets' : 'Ticket')
                            . ' '
                            . __('and')
                            . ' '
                            . $contributorsCount
                            . ' '
                            . __($contributorsCount > 1 ? 'Contributors' : 'Contributor')
                        . '</div>
                        <div class="text-xs w-full flex items-center gap-2 mt-2">
                            <a class="text-primary-400 hover:text-primary-500 hover:cursor-pointer"
                               href="' . route('filament.resources.projects.view', $project) . '">
                                ' . __('View details') . '
                            </a>
                            <span class="text-gray-300">|</span>
                            <a class="text-primary-400 hover:text-primary-500 hover:cursor-pointer"
                               href="' . route('filament.pages.kanban/{project}', ['project' => $project->id]) . '">
                                ' . __('Tickets') . '
                            </a>
                        </div>
                    '));
        }
        return $cards;
    }
}
