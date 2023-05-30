<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositUang extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pegawai',
        'id_member',
        'tanggal',
        'sisa_uang',
        'jumlah_uang',
        'bonus',
        'total_uang'
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
