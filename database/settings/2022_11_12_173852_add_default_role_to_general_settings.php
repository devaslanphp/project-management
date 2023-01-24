<?php

use App\Models\Role;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddDefaultRoleToGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.default_role');
    }
}
