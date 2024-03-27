<?php

declare(strict_types=1);

namespace App\Modules\LaravelSettings\SettingsRepositories;

class DatabaseSettingsRepository extends \Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository
{
    public function updatePropertiesPayload(string $group, array $properties): void
    {
        $propertiesInBatch = collect($properties)->map(function ($payload, $name) use ($group) {
            return [
                'group' => $group,
                'name' => $name,
                'payload' => json_encode($payload),
            ];
        })->values()->toArray();

        foreach ($propertiesInBatch as $batch) {
            $this->getBuilder()
                ->updateOrInsert([
                    'group' => $batch['group'],
                    'name' => $batch['name']
                ], ['payload' => $batch['payload']]);
        }
    }
}
