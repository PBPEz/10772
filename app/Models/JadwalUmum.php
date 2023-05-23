<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalUmum extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_kelas",
        "tanggal",
        "hari",
        "sesi"
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_kelas');
    }

    public function jadwalUmum()
    {
        return $this->hasMany(JadwalUmum::class, 'id_jadwal_umum');
    }
}