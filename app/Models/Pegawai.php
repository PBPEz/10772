<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Pegawai extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'nama_pegawai',
        'tanggal_lahir',
        'email',
        'nomor_telepon',
        'role',
        'password'
    ];

    protected $hidden = [
        'token',
    ];

    public function aktivasiMember()
    {
        return $this->belongsTo(AktivasiMember::class, 'id_pegawai');
    }
}
