<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class RoadMap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.road-map';

    protected static ?string $slug = 'road-map';

    protected static ?int $navigationSort = 4;

    protected static function getNavigationLabel(): string
    {
        return __('Road map');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Management');
    }
}
