<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'nip',
        'nama_peminjam',
        'masa_pinjam',
        'tgl_pinjam',
        'tgl_kembali',
        'keperluan',
        'seksi',
        'status',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}