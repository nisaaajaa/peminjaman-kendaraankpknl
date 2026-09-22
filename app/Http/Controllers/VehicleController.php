<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Loan;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // Menampilkan halaman utama
    public function index()
    {
        // Mengambil data dari database, jika kosong buatkan data Toyota Rush
        $vehicles = Vehicle::all();

        if ($vehicles->isEmpty()) {
            $vehicles = collect([
                (object)[
                    'id' => 1,
                    'nama_kendaraan' => 'Toyota Rush',
                    'plat_nomor' => 'BE 1007 FZ',
                    'foto' => 'toyotarush.jpg',
                    'status' => 'tersedia'
                ]
            ]);
        }

        return view('home', compact('vehicles'));
    }

    // Menampilkan form peminjaman
    public function create($id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            $vehicle = (object)[
                'id' => 1,
                'nama_kendaraan' => 'Toyota Rush',
                'plat_nomor' => 'BE 1007 FZ',
                'foto' => 'toyotarush.jpg',
                'status' => 'tersedia'
            ];
        }

        if (isset($vehicle->status) && $vehicle->status === 'dipinjam') {
            return redirect('/')->with('error', 'Kendaraan sedang digunakan!');
        }

        return view('form', compact('vehicle'));
    }

    // Menyimpan permohonan peminjaman
    public function store(Request $request, $id)
    {
        $request->validate([
            'nama_peminjam' => 'required',
            'masa_pinjam'   => 'required',
            'keperluan'     => 'required',
        ]);

        $vehicle = Vehicle::find($id);

        if ($vehicle) {
            $vehicle->update(['status' => 'dipinjam']);
        }

        Loan::create([
            'vehicle_id'    => $id,
            'nama_peminjam' => $request->nama_peminjam,
            'masa_pinjam'   => $request->masa_pinjam,
            'keperluan'     => $request->keperluan,
        ]);

        return redirect('/')->with('success', 'Berhasil mengajukan peminjaman!');
    }
}