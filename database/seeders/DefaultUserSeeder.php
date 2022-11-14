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
        if (User::where('email', 'eloufirhatim@gmail.com')->count() == 0) {
            User::create([
                'name' => 'EL OUFIR Hatim',
                'email' => 'eloufirhatim@gmail.com',
                'password' => '$2a$12$h/.Jq3QGHYoJBLBo8hw1mOtJOmtU.BVJFbBWFC7XAVXmE5gOjdXV.', // Passw@rd
                'email_verified_at' => now()
            ]);
        }
    }
}
