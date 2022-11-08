<?php

namespace Database\Seeders;

use App\Models\TicketType;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    private array $data = [
        [
            'name' => 'Task',
            'icon' => 'heroicon-o-check-circle',
            'color' => '#00FFFF',
            'is_default' => true
        ],
        [
            'name' => 'Evolution',
            'icon' => 'heroicon-o-clipboard-list',
            'color' => '#008000',
            'is_default' => false
        ],
        [
            'name' => 'Bug',
            'icon' => 'heroicon-o-x',
            'color' => '#ff0000',
            'is_default' => false
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $item) {
            TicketType::firstOrCreate($item);
        }
    }
}
