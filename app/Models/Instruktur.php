<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_instruktur',
        'tanggal_lahir',
        'nomor_telepon',
        'email',
        'status',
        'gender',
        'password',
        'role'
    ];

    public function presensiInstruktur()
    {
        return $this->belongsTo(PresensiInstruktur::class, 'id_instruktur');
    }
}
