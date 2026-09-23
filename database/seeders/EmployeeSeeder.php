<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employees')->delete();

        $employees = [
            'MOHAMAD RIYANTO', 'MARYANTO', 'RAHMAD SIGIT', 'MUHAMMAD GANJAR NUGRAHA',
            'MUCHTAR NURWAHIDZAIN', 'BARNO', 'ISMARUDDIN', 'RUBIN HARYADI', 'JOHAN WAHYUDI',
            'YOGI WISAKSONO', 'MELVIN INDRIANI', 'ADE HENDRA VASKAH TARIGAN', 'ANGGA APRIANTO',
            'HABIB BURAKHMAN', 'WAHIDIN HARYA DITAMA', 'WIDI WIDAYAT', 'ADHYTIA PRATAMA ALBEN',
            'MEYZAR AHMAD', 'MUHAMAD RIZKIANA GUMILANG', 'AHMAD NOPRAN', 'AMELIA RIZKYANTI',
            'SANTO SULANDRY'
        ];

        $nipStart = 198001012005011000;
        
        foreach ($employees as $index => $name) {
            DB::table('employees')->insert([
                'nama_pegawai' => $name,
                'nip' => (string)($nipStart + $index + 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
