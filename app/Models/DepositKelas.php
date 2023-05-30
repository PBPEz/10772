<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositKelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pegawai',
        'id_kelas',
        'tanggal',
        'jumlah_bayar_kelas',
        'jumlah_kelas',
        'bonus_kelas',
        'total_kelas',
        'tanggal_kadaluarsa'
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id');
    }
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id');
    }
}
