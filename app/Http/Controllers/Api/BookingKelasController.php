<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingKelas;
use App\Models\JadwalUmum;
use App\Models\Member;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookingKelas = DB::table('booking_kelas')->join('members', 'booking_kelas.id_member','=','members.id')
        ->join('jadwal_umums','jadwal_umums.id_kelas','=','jadwal_umums.id')
        ->join('kelas','jadwal_umums.id_kelas','=','kelas.id')
        ->join('instrukturs','kelas.id_instruktur','=','instrukturs.id')
        ->select('booking_kelas.*','members.nama_member','members.deposit_uang',
                'jadwal_umums.tanggal','jadwal_umums.hari','jadwal_umums.sesi',
                'kelas.nama_kelas','kelas.harga','instrukturs.nama_instruktur')
        ->get();

        if(count($bookingKelas) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bookingKelas
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_member' => 'required',
            'id_jadwal_umum' => 'required',
            'jam_presensi' => ''
        ]);

        $id = IdGenerator::generate(['table' => 'booking_kelas', 'length' => 10, 'prefix' => date('y.m.')]);

        $tanggal = JadwalUmum::where('id',$request->id_jadwal_umum)->first();

        $storeData['id'] = $id;
        $storeData['tanggal_kelas'] = $tanggal->tanggal;

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 

        if(BookingKelas::where('id_member',$request->id_member)->first()){
            
            if(BookingKelas::where('tanggal_kelas',$storeData['tanggal_kelas'])->first())
                return response(['Member sudah booking pada hari tersebut!']);
        }

        $member = Member::where('id',$request->id_member)->first();

        if($member->status == "tidak aktif"){
            return response(['message' => 'Member tidak aktif!'], 400);
        }

        $count = JadwalUmum::where('tanggal', $request->tanggal)
        ->where('sesi', $request->sesi)
        ->count();
        
        iF($count >= 20)
            return response(['message' => 'Kuota kelas pada waktu tersebut sudah penuh!']);

        $bookingKelas = BookingKelas::create($storeData);
        return response([
            'message' => 'Add booking kelas Success',
            'data' => $bookingKelas,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $bookingKelas = BookingKelas::find($id);
        if(is_null($bookingKelas)){
            return response([
                'message' => 'Booking kelas Not Found',
                'data' => null
            ], 404);
        }
    
        if($bookingKelas->jam_presensi != NULL){
            return response([
                'message' => 'Member sudah presensi!',
                'data' => 'Member sudah presensi!'
            ], 200);
        }

        $bookingKelas->jam_presensi = date('H:i:s');

        if($bookingKelas->save()){
            return response([
                'message' => 'Update Booking Gym Success',
                'data' => $bookingKelas
            ], 200);
        }

        return response([
            'message' => 'Update Presensi gym Failed',
            'data' => null
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookingKelas = BookingKelas::find($id);

        if(!is_null($bookingKelas)){
            return response([
                'message' => 'Retrieve booking kelas Success',
                'data' => $bookingKelas   
            ], 200);
        }

        return response([
            'message' => 'Booking kelas Not Found',
            'data' => null
        ], 404);
    }
}
