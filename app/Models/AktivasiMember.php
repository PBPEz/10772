<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivasiMember extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id_aktivasi',
        'id_pegawai',
        'id_member',
        'jumlah_bayar',
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id');
    }

    public function member()
    {
        return $this->hasMany(Member::class, 'id');
    }
}
