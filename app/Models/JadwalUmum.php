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
        "sesi",
        "status"
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id');
    }

    public function jadwalUmum()
    {
        return $this->hasMany(JadwalUmum::class, 'id');
    }
}