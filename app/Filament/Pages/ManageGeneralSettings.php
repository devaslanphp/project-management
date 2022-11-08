<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = GeneralSettings::class;

    protected static function getNavigationLabel(): string
    {
        return __('General');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    Grid::make(3)
                        ->schema([
                            FileUpload::make('site_logo')
                                ->label(__('Site logo'))
                                ->helperText(__('This is the plaform logo (e.g. Used in site favicon)'))
                                ->image()
                                ->columnSpan(1)
                                ->maxSize(config('system.max_file_size')),

                            Grid::make(1)
                                ->columnSpan(2)
                                ->schema([
                                    TextInput::make('site_name')
                                        ->label(__('Site name'))
                                        ->helperText(__('This is the platform name'))
                                        ->default(fn() => config('app.name'))
                                        ->required(),

                                    Checkbox::make('enable_registration')
                                        ->label(__('Enable registration?'))
                                        ->helperText(__('If enabled, any user can create an account in this platform.
                                                        But an administration need to give them permissions.')),
                                ]),
                        ]),
                ]),
        ];
    }
}
