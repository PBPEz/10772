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
        $aktivitasGym = BookingGym::where('tanggal', '<', $tanggalCetak)
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

    public function laporanInstruktur(Request $request)
    {
        $bulan = Carbon::now()->month;
        if ($request->has('month') && !empty($request->month)) {
            $bulan = $request->month;
        }
        // dd($bulan);
        //* Tanggal Cetak
        $tanggalCetak = Carbon::now();
        $kinerjaInstruktur = DB::select('
        SELECT i.nama_instruktur,
            SUM(CASE WHEN pi.status IS NOT NULL THEN 1 ELSE 0 END) AS jumlah_hadir,
            SUM(CASE WHEN iz.id IS NOT NULL THEN 1 ELSE 0 END) AS jumlah_izin,
            IFNULL(i.akumulasi_terlambat, 0) AS akumulasi_terlambat
        FROM instrukturs AS i
        LEFT JOIN presensi_instrukturs AS pi ON i.id = pi.id_instruktur AND MONTH(pi.created_at) = ?
        LEFT JOIN izin_instrukturs AS iz ON i.id = iz.id_instruktur AND MONTH(iz.created_at) = ?
        GROUP BY i.nama_instruktur, i.akumulasi_terlambat   
    ', [$bulan, $bulan]);
        return response([
            'data' => $kinerjaInstruktur,
            'tanggal_cetak' => $tanggalCetak,
        ]);
    }

    public function laporanPendapatan(Request $request){
        //* Cek Tahun
        $year = Carbon::now()->year;
        $tanggalCetak = Carbon::now();
        if ($request->has('year') && !empty($request->year)) {
            $year = $request->year;
        }
        //*Group Pendapatannya perbulan
        //*Group Tampilan Pertahun -> Request->Year
        //*Group 
        $pendapatanPerTahun = DB::select("
        SELECT
            bulan.nama_bulan,
            COALESCE(SUM(jumlah_bayar), 0) AS total_pendapatan_aktivasi,
            COALESCE(SUM(pendapatan_reguler + pendapatan_paket), 0) AS total_pendapatan_deposit,
            COALESCE(SUM(jumlah_bayar + pendapatan_reguler + pendapatan_paket), 0) AS total_pendapatan
        FROM (
            SELECT 1 AS bulan_id, 'January' AS nama_bulan UNION ALL
            SELECT 2 AS bulan_id, 'February' AS nama_bulan UNION ALL
            SELECT 3 AS bulan_id, 'March' AS nama_bulan UNION ALL
            SELECT 4 AS bulan_id, 'April' AS nama_bulan UNION ALL
            SELECT 5 AS bulan_id, 'May' AS nama_bulan UNION ALL
            SELECT 6 AS bulan_id, 'June' AS nama_bulan UNION ALL
            SELECT 7 AS bulan_id, 'July' AS nama_bulan UNION ALL
            SELECT 8 AS bulan_id, 'August' AS nama_bulan UNION ALL
            SELECT 9 AS bulan_id, 'September' AS nama_bulan UNION ALL
            SELECT 10 AS bulan_id, 'October' AS nama_bulan UNION ALL
            SELECT 11 AS bulan_id, 'November' AS nama_bulan UNION ALL
            SELECT 12 AS bulan_id, 'December' AS nama_bulan
        ) AS bulan
        LEFT JOIN (
            SELECT
                MONTH(am.created_at) AS bulan_id,
                am.jumlah_bayar,
                0 AS pendapatan_reguler,
                0 AS pendapatan_paket
            FROM aktivasi_members AS am
            WHERE YEAR(am.created_at) = $year
            UNION ALL
            SELECT
                MONTH(du.tanggal) AS bulan_id,
                0 AS jumlah_bayar,
                du.jumlah_uang AS pendapatan_reguler,
                dk.jumlah_bayar_kelas AS pendapatan_paket
            FROM deposit_uang AS du
            INNER JOIN deposit_kelas AS dk ON du.tanggal = dk.tanggal
            WHERE YEAR(du.tanggal) = $year
        ) AS transaksi ON bulan.bulan_id = transaksi.bulan_id
        GROUP BY bulan.bulan_id, bulan.nama_bulan
        ORDER BY bulan.bulan_id
    ");

        return response([
            'data' => $pendapatanPerTahun,
            'tanggal_cetak' => $tanggalCetak,
        ]);
    }
}