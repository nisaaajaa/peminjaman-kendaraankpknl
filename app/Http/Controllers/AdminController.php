<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $pendingLoans = Loan::with('vehicle')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $approvedLoans = Loan::with('vehicle')->where('status', 'approved')->orderBy('created_at', 'desc')->get();
        $logLoans = Loan::with('vehicle')->whereIn('status', ['returned', 'rejected'])->orderBy('updated_at', 'desc')->get();
        
        $vehicles = Vehicle::all();
        $employees = \App\Models\Employee::all();

        return view('admin.dashboard', compact('pendingLoans', 'approvedLoans', 'logLoans', 'vehicles', 'employees'));
    }

    // --- CRUD PEGAWAI ---
    public function storeEmployee(Request $request)
    {
        $request->validate([
            'nip' => 'required|numeric|unique:employees,nip',
            'nama_pegawai' => 'required|string|max:255',
        ]);

        \App\Models\Employee::create([
            'nip' => $request->nip,
            'nama_pegawai' => strtoupper($request->nama_pegawai),
        ]);

        return redirect()->back()->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function updateEmployee(Request $request, $id)
    {
        $employee = \App\Models\Employee::findOrFail($id);

        $request->validate([
            'nip' => 'required|numeric|unique:employees,nip,' . $employee->id,
            'nama_pegawai' => 'required|string|max:255',
        ]);

        $employee->update([
            'nip' => $request->nip,
            'nama_pegawai' => strtoupper($request->nama_pegawai),
        ]);

        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function deleteEmployee($id)
    {
        $employee = \App\Models\Employee::findOrFail($id);
        $employee->delete();

        return redirect()->back()->with('success', 'Pegawai berhasil dihapus.');
    }

    public function exportEmployeesCsv()
    {
        $employees = \App\Models\Employee::all();
        $csvFileName = 'data_pegawai_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(
            function () use ($employees) {
                $handle = fopen('php://output', 'w');
                // Header CSV
                fputcsv($handle, ['NIP', 'Nama Pegawai']);
                foreach ($employees as $employee) {
                    fputcsv($handle, [$employee->nip, $employee->nama_pegawai]);
                }
                fclose($handle);
            },
            200,
            $headers
        );
    }

    public function importEmployeesCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->path(), 'r');

        $header = true;
        $count = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if ($header) {
                $header = false;
                continue; // Skip baris pertama (header)
            }

            if (count($row) >= 2) {
                $nip = trim($row[0]);
                $nama = trim($row[1]);

                if (!empty($nip) && !empty($nama)) {
                    \App\Models\Employee::updateOrCreate(
                        ['nip' => $nip],
                        ['nama_pegawai' => strtoupper($nama)]
                    );
                    $count++;
                }
            }
        }
        fclose($handle);

        return redirect()->back()->with('success', "Berhasil memproses $count data pegawai dari CSV.");
    }

    // --- CRUD KENDARAAN ---
    public function storeVehicle(Request $request)
    {
        $request->validate([
            'nama_kendaraan' => 'required|string|max:255',
            'plat_nomor' => 'required|string|max:255',
        ]);

        Vehicle::create([
            'nama_kendaraan' => $request->nama_kendaraan,
            'plat_nomor' => $request->plat_nomor,
            'status' => 'tersedia',
        ]);

        return redirect()->back()->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function updateVehicle(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'nama_kendaraan' => 'required|string|max:255',
            'plat_nomor' => 'required|string|max:255',
        ]);

        $vehicle->update([
            'nama_kendaraan' => $request->nama_kendaraan,
            'plat_nomor' => $request->plat_nomor,
        ]);

        return redirect()->back()->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function deleteVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        // Prevent deletion if vehicle is currently loaned out (approved)
        $activeLoan = Loan::where('vehicle_id', $id)->where('status', 'approved')->first();
        if ($activeLoan) {
            return redirect()->back()->withErrors(['error' => 'Kendaraan tidak dapat dihapus karena sedang dipinjam.']);
        }

        $vehicle->delete();
        return redirect()->back()->with('success', 'Kendaraan berhasil dihapus.');
    }

    // --- AKSI PEMINJAMAN ---
    public function approveLoan($id)
    {
        $loan = Loan::findOrFail($id);
        $vehicle = Vehicle::find($loan->vehicle_id);

        if (!$vehicle || $vehicle->status === 'dipinjam') {
            return redirect()->back()->withErrors(['error' => 'Kendaraan tidak tersedia atau sudah dipinjam orang lain.']);
        }

        $loan->update(['status' => 'approved']);
        $vehicle->update(['status' => 'dipinjam']);

        return redirect()->back()->with('success', 'Peminjaman disetujui.');
    }

    public function rejectLoan($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Peminjaman ditolak.');
    }

    public function returnLoan($id)
    {
        $loan = Loan::findOrFail($id);
        if ($loan->status !== 'approved') {
            return redirect()->back()->withErrors(['error' => 'Hanya peminjaman aktif yang bisa dikembalikan.']);
        }

        $loan->update(['status' => 'returned']);
        
        $vehicle = Vehicle::find($loan->vehicle_id);
        if ($vehicle) {
            $vehicle->update(['status' => 'tersedia']);
        }

        return redirect()->back()->with('success', 'Kendaraan berhasil dikembalikan.');
    }
}
