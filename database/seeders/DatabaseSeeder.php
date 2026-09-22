<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Vehicle::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data 5 Kendaraan Dinas Riil KPKNL Metro
        Vehicle::create(['nama_kendaraan' => 'Toyota Rush - BE 1007 FZ', 'status' => 'tersedia']);
        Vehicle::create(['nama_kendaraan' => 'Toyota Rush - BE 1068 FZ', 'status' => 'tersedia']);
        Vehicle::create(['nama_kendaraan' => 'Toyota Kijang Innova - BE 1101 FZ', 'status' => 'tersedia']);
        Vehicle::create(['nama_kendaraan' => 'Mitsubishi Xpander - BE 1006 FZ', 'status' => 'tersedia']);
        Vehicle::create(['nama_kendaraan' => 'Toyota Hilux - B 9440 PSE', 'status' => 'tersedia']); // <-- Mobil ke-5
    }
}