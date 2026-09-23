<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Loan;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VehicleController extends Controller
{
    // Menampilkan halaman utama
    public function index()
    {
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
            'nama_pegawai' => 'required',
            'nip'          => 'required',
            'seksi'        => 'required',
            'keperluan'    => 'required',
            'tgl_pinjam'   => 'required|date',
            'tgl_kembali'  => 'required|date|after_or_equal:tgl_pinjam',
            'cf-turnstile-response' => 'required',
        ]);

        // 1. Verifikasi Cloudflare Turnstile
        $turnstileResponse = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => env('TURNSTILE_SECRET_KEY'),
            'response' => $request->input('cf-turnstile-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$turnstileResponse->json('success')) {
            return back()->with('error', 'Verifikasi Keamanan gagal. Silakan coba lagi.')->withInput();
        }

        // 2. Verifikasi NIP Pegawai
        $employee = Employee::where('nama_pegawai', $request->nama_pegawai)
                            ->where('nip', $request->nip)
                            ->first();
                            
        if (!$employee) {
            return back()->with('error', 'NIP tidak cocok dengan nama pegawai!')->withInput();
        }

        // 3. Simpan Data Peminjaman
        // Kendaraan tidak langsung di-update menjadi 'dipinjam'
        // Status peminjaman defaultnya adalah 'pending' (menunggu persetujuan Admin)

        $masaPinjam = $request->tgl_pinjam . ' s.d. ' . $request->tgl_kembali;

        Loan::create([
            'vehicle_id'    => $id,
            'nip'           => $request->nip,
            'nama_peminjam' => $request->nama_pegawai,
            'masa_pinjam'   => $masaPinjam,
            'keperluan'     => $request->keperluan . ' (Seksi: ' . $request->seksi . ')',
            'status'        => 'pending',
        ]);

        return redirect('/')->with('success', 'Berhasil mengajukan peminjaman!');
    }
}