<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiInstruktur extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_jadwal_umum',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'keterlambatan'
    ];

    public function instruktur()
    {
        return $this->hasMany(Instruktur::class, 'id');
    }
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id');
    }
}
