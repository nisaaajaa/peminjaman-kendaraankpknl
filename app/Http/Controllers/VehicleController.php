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
        $vehicles = Vehicle::with(['loans' => function($query) {
            $query->whereIn('status', ['pending', 'approved'])->orderBy('created_at', 'desc');
        }])->get();

        return view('home', compact('vehicles'));
    }

    // Menampilkan form peminjaman
    public function create($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        if ($vehicle->status === 'dipinjam') {
            return redirect('/')->with('error', 'Kendaraan sedang digunakan!');
        }

        $employees = Employee::orderBy('nama_pegawai', 'asc')->get();

        return view('form', compact('vehicle', 'employees'));
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

        // Verifikasi bahwa kendaraan ada dan tersedia
        $vehicle = Vehicle::where('id', $id)->where('status', 'tersedia')->first();
        if (!$vehicle) {
            return redirect('/')->with('error', 'Kendaraan tidak ditemukan atau tidak tersedia!');
        }

        // 1. Verifikasi Cloudflare Turnstile
        $turnstileResponse = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('services.turnstile.secret_key'),
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
            'vehicle_id'    => $vehicle->id,
            'nip'           => $request->nip,
            'nama_peminjam' => $request->nama_pegawai,
            'masa_pinjam'   => $masaPinjam, // kept for backward compatibility
            'tgl_pinjam'    => $request->tgl_pinjam,
            'tgl_kembali'   => $request->tgl_kembali,
            'keperluan'     => $request->keperluan,
            'seksi'         => $request->seksi,
            'status'        => 'pending',
        ]);

        return redirect('/')->with('success', 'Berhasil mengajukan peminjaman!');
    }
}