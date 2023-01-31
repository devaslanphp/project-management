<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddLoginFormOidcEnabledFlagsToGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.enable_login_form', config('system.login_form.is_enabled'));
        $this->migrator->add('general.enable_oidc_login', config('services.oidc.is_enabled'));
    }
}
