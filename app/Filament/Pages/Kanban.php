<?php

namespace App\Filament\Pages;

use InvadersXX\FilamentKanbanBoard\Pages\FilamentKanbanBoard;

class Kanban extends FilamentKanbanBoard
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments';

    public bool $sortable = true;

    public bool $sortableBetweenStatuses = true;

    protected static bool $shouldRegisterNavigation = false;
}
