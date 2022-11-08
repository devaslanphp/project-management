<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'EL OUFIR Hatim',
            'email' => 'eloufirhatim@gmail.com',
            'password' => bcrypt('Passw@rd'),
            'email_verified_at' => now()
        ]);
    }
}
