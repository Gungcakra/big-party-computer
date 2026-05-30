<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name'     => 'Administrator',
            'email'    => 'admin@bigpartycomputer.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::factory()->create([
            'name'     => 'Teknisi Utama',
            'email'    => 'teknisi@bigpartycomputer.com',
            'password' => Hash::make('password'),
            'role'     => 'teknisi',
        ]);
    }
}
