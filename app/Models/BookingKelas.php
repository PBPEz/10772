<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingKelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_member',
        'id_jadwal_umum',
        'tanggal_kelas',
        'jam_presensi'
    ];

    public function member()
    {
        return $this->hasMany(Member::class, 'id');
    }
    public function jadwalUmum()
    {
        return $this->hasMany(JadwalUmum::class, 'id');
    }
}

