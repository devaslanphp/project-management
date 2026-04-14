<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class JiraSettings extends Settings
{
    public string $host;
    public string $username;
    public string $token;

    public static function group(): string
    {
        return 'jira';
    }

    public static function encrypted(): array
    {
        return ['token'];
    }
}
