<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiInstrukturController extends Controller
{
    public function index()
    {
        $presensiInstruktur = DB::table('presensi_instrukturs')
            ->join('jadwal_umums', 'presensi_instrukturs.id_jadwal_umum', '=', 'jadwal_umums.id')
            ->join('kelas', 'jadwal_umums.id_kelas', '=', 'kelas.id')
            ->join('instrukturs', 'kelas.id_instruktur', '=', 'instrukturs.id')
            ->select(
                'presensi_instrukturs.*',
                'instrukturs.nama_instruktur',
                'kelas.nama_kelas' //Select (Mengambil data dari masing-masing tabel)
            )
            ->get();
        
        
        if (count($presensiInstruktur) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $presensiInstruktur
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }
}
