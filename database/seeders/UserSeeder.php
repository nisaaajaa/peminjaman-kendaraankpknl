<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin.kpknlmetro@kemenkeu.go.id'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('KpknlMetro2026!')
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin'],
            [
                'name' => 'Admin KPKNL',
                'password' => Hash::make('kpknlmetro')
            ]
        );
    }
}
