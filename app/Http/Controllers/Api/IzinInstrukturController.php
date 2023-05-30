<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IzinInstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $izinInstruktur = DB::table('izin_instrukturs')
            ->join('jadwal_umums', 'izin_instrukturs.id_jadwal_umum', '=', 'jadwal_umums.id')
            ->join('instrukturs', 'izin_instrukturs.id_instruktur', '=', 'instrukturs.id')
            ->select(
                'presensi_instrukturs.*',
                'instrukturs.nama_instruktur' //Select (Mengambil data dari masing-masing tabel)
            )
            ->get();
        
        
        if (count($izinInstruktur) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $izinInstruktur
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }
}
