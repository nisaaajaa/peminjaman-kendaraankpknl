<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_kendaraan',
        'plat_nomor',
        'foto',
        'status',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
