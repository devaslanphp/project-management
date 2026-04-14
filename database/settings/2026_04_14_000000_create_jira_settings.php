<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateJiraSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('jira.host', '');
        $this->migrator->add('jira.username', '');
        $this->migrator->addEncrypted('jira.token', '');
    }
}
