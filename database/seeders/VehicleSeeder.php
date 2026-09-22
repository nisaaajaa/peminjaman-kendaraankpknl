<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        Vehicle::create([
            'nama_kendaraan' => 'Avanza 2022',
            'status' => 'tersedia'
        ]);

        Vehicle::create([
            'nama_kendaraan' => 'Innova',
            'status' => 'tersedia'
        ]);
    }
}