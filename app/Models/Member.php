<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $fillable = [
        'id',
        'nama_member',
        'tanggal_lahir',
        'email',
        'deposit_uang',
        'deposit_kelas',
        'nomor_telepon',
        'gender',
        'status',
        'username',
        'password',
        'masa_berlaku'
    ];

    public function aktivasiMember()
    {
        return $this->belongsTo(AktivasiMember::class, 'id_member');
    }
    public function bookingGym()
    {
        return $this->belongsTo(PresensiGym::class,'id_member');
    }
    public function depositUang()
    {
        return $this->belongsTo(DepositUang::class,'id_member');
    }
}
