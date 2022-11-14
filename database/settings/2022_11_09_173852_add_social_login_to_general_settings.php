<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddSocialLoginToGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.enable_social_login', true);
    }
}
