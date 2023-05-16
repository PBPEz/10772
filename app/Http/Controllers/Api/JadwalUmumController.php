<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InstrukturResource;
use App\Models\JadwalUmum;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class JadwalUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jadwalUmums = JadwalUmum::all();

        $jadwalUmum = DB::table('jadwal_umums')->join('kelas','jadwal_umums.id_kelas','=','kelas.id')
        ->select('jadwal_umums.*','kelas.nama_kelas')
        ->get();

        if (count($jadwalUmums) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'tanggal' => 'required',
            'hari' => 'required',
            'sesi' => 'required|numeric'
        ]);

        if ($validator->fails())
            return response(['message' => $validator->errors()], 400);

        $jadwalUmum = JadwalUmum::create($storeData);
        return response([
            'message' => 'Add Instruktur Success',
            'data' => $jadwalUmum
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jadwalUmum = JadwalUmum::find($id);

        if (!is_null($jadwalUmum)) {
            return response([
                'message' => 'Retrieve jadwal umum Success',
                'data' => $jadwalUmum
            ], 200);
        }

        return response([
            'message' => 'Jadwal Umum Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $jadwalUmum = JadwalUmum::find($id);
        if (is_null($jadwalUmum)) {
            return response([
                'message' => 'Jadwal Umum Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_kelas' => 'required',
            'tanggal' => 'required',
            'hari' => 'required',
            'sesi' => 'required|numeric'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $jadwalUmum->id_kelas = $updateData['id_kelas'];
        $jadwalUmum->tanggal = $updateData['tanggal'];
        $jadwalUmum->hari = $updateData['hari'];
        $jadwalUmum->sesi = $updateData['sesi'];

        if ($jadwalUmum->save()) {
            return response([
                'message' => 'Update jadwal umum Success',
                'data' => $jadwalUmum
            ], 200);
        }

        return response([
            'message' => 'Update Jadwal Umum Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jadwalUmum = JadwalUmum::find($id);

        if (is_null($jadwalUmum)) {
            return response([
                'message' => 'Jadwal Umum Not Found',
                'data' => null
            ], 404);
        }

        if ($jadwalUmum->delete()) {
            return response([
                'message' => 'Delete Jadwal Umum Success',
                'data' => $jadwalUmum
            ], 200);
        }

        return response([
            'message' => 'Delete Jadwal Umum Failed',
            'data' => null
        ], 400);
    }
}
