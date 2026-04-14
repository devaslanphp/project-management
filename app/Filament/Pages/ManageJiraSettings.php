<?php

namespace App\Filament\Pages;
use BackedEnum;

use App\Settings\JiraSettings;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Pages\SettingsPage;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;

class ManageJiraSettings extends SettingsPage
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cloud-arrow-up';

    protected static string $settings = JiraSettings::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('Import from Jira');
    }

    public function getHeading(): string|Htmlable
    {
        return __('Manage Jira settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('Jira');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('Jira credentials'))
                ->description(__('Configure your Jira connection credentials. The API token is stored encrypted.'))
                ->columnSpanFull()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('host')
                                ->label(__('Host'))
                                ->helperText(__('The URL used to access your Jira account (e.g. https://yourcompany.atlassian.net)'))
                                ->required(),

                            TextInput::make('username')
                                ->label(__('Username'))
                                ->helperText(__('Your Jira account username (email)'))
                                ->required(),

                            TextInput::make('token')
                                ->label(__('API Token'))
                                ->helperText(__('Your Jira account API token'))
                                ->password()
                                ->required(),
                        ]),
                ]),
        ]);
    }

    public function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()->label(__('Save'));
    }
}
