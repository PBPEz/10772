<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingGym;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanGym(Request $request)
    {
        $bulan = Carbon::now()->month;
        if ($request->has('month') && !empty($request->month)) {
            $bulan = $request->month;
        }
        //* Tanggal Cetak
        $tanggalCetak = Carbon::now();
        $aktivitasGym = BookingGym::where('tanggal','<',$tanggalCetak)
        ->whereNotNull('jam_booking')
        ->whereMonth('tanggal', $bulan) //* lewat parmas
        ->get()
        ->groupBy(function ($item) {
            //*group by tanggal
            $carbonDate = Carbon::createFromFormat('Y-m-d', $item->tanggal);
            return $carbonDate->format('Y-m-d');
        });
        //* Data yang diambil data booking gym yang udah lewat(tanggal sesi gymnya status kehadiran 1) dan tidak dibatalin
        
        //* Count 
        $responseData = [];
        
        foreach ($aktivitasGym as $tanggal => $grup) {
            $count = $grup->count();
            $responseData[] = [
                'tanggal' => $tanggal,
                'count' => $count,
            ];
        }
        
        return response([
            'data' => $responseData,
            'tanggal_cetak' => $tanggalCetak
        ]);
    }

    public function laporanKelas(Request $request)
    {
        $bulan = Carbon::now()->month;
        if ($request->has('month') && !empty($request->month)) {
            $bulan = $request->month;
        }
        //* Tanggal Cetak
        $tanggalCetak = Carbon::now();
        $aktivitasKelas = DB::select('
            SELECT k.nama_kelas AS kelas, i.nama_instruktur AS instruktur, COUNT(bk.id) AS jumlah_peserta, 
                COUNT(CASE WHEN ju.status = "libur" THEN 1 ELSE NULL END) AS jumlah_libur
            FROM booking_kelas AS bk
            JOIN jadwal_umums AS ju ON bk.id_jadwal_umum = ju.id
            JOIN kelas AS k ON ju.id_kelas = k.id
            JOIN instrukturs AS i ON k.id_instruktur = i.id
            WHERE MONTH(ju.tanggal) = ?
            GROUP BY k.nama_kelas, i.nama_instruktur
        ', [$bulan]);

        //akumulasi terlambat direset tiap bulan jam mulai tiap bulan - jam selesai bulan         
        return response([
            'data' => $aktivitasKelas,
            'tanggal_cetak' => $tanggalCetak,
        ]);
        
    }
}
