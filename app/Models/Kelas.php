<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        "nama_kelas",
        "id_instruktur",
        "harga",
    ];

    public function jadwalUmum()
    {
        return $this->belongsTo(JadwalUmum::class, 'id_kelas');
    }

    public function presensiInstruktur()
    {
        return $this->belongsTo(PresensiInstruktur::class, 'id_kelas');
    }
}