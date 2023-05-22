<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingGym extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_member',
        'tanggal',
        'sesi',
        'jam_booking'
    ];

    public function member()
    {
        return $this->hasMany(Member::class, 'id');
    }
}
