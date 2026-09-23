<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'nama_kendaraan' => 'Toyota Rush',
                'plat_nomor' => 'BE 1007 FZ',
                'foto' => 'toyotarush.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama_kendaraan' => 'Toyota Rush',
                'plat_nomor' => 'BE 1068 FZ',
                'foto' => 'toyotarush2.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama_kendaraan' => 'Toyota Kijang Innova',
                'plat_nomor' => 'BE 1101 FZ',
                'foto' => 'innova.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama_kendaraan' => 'Mitsubishi Xpander',
                'plat_nomor' => 'BE 1006 FZ',
                'foto' => 'xpander.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama_kendaraan' => 'Toyota Hilux',
                'plat_nomor' => 'B 9440 PSE',
                'foto' => 'hilux.jpg',
                'status' => 'tersedia'
            ],
        ];

        foreach ($vehicles as $v) {
            Vehicle::updateOrCreate(
                ['plat_nomor' => $v['plat_nomor']],
                $v
            );
        }
    }
}