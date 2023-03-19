<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(5)->create();
        \App\Models\User::factory()->create([
            'username' => 'trb123',
            'password' => Hash::make('Abcdefgh10'),
            ]);
        \App\Models\Sms::factory(10)->create();
    }
}
