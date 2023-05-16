<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AktivasiMember;
use App\Models\Member;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\DB;

class AktivasiMemberController extends Controller
{
    public function index()
    {
        $aktivasiMembers = AktivasiMember::all();

        $aktivasi = DB::table('aktivasi_members')->join('members', 'aktivasi_members.id_member','=','members.id')
        -> join('pegawais', 'aktivasi_members.id_pegawai','=','pegawais.id')
        -> select('aktivasi_members.*','members.nama_member', 'pegawais.nama_pegawai','members.status','members.id')
        ->get();

        if (count($aktivasiMembers) >= 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $aktivasi,
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
        $validate = Validator::make($storeData, [
            'id_pegawai' => 'required',
            'id_member' => 'required',
            'jumlah_bayar' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $id = IdGenerator::generate(['table' => 'aktivasi_members', 'length' => 10, 'prefix' => date('y.m.')]);

        $storeData['id_aktivasi'] = $id;

        $aktivasiMember = AktivasiMember::create($storeData);
        return response([
            'message' => 'Add aktivasi Success',
            'data' => $aktivasiMember
        ], 200);
    }

    public function show($id)
    {
        $aktivasiMember = AktivasiMember::find($id);

        if (!is_null($aktivasiMember)) {
            return response([
                'message' => 'Retrieve aktivasi member Success',
                'data' => $aktivasiMember
            ], 200);
        }

        return response([
            'message' => 'Aktivasi Member Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $aktivasiMember = AktivasiMember::find($id);
        if(is_null($aktivasiMember)){
            return response([
                'message' => 'Aktivasi Member Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_pegawai' => 'required',
            'id_member' => 'required',
            'jumlah_bayar' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $aktivasiMember->id_pegawai = $updateData['id_pegawai'];
        $aktivasiMember->id_member = $updateData['id_member'];
        $aktivasiMember->jumlah_bayar = $updateData['jumlah_bayar'];
        $aktivasiMember->deposit_uang = $updateData['jenis_bayar'];

        if($aktivasiMember->save()){
            return response([
                'message' => 'Update aktivasi member Success',
                'data' => $aktivasiMember
            ], 200);
        }

        return response([
            'message' => 'Update Aktivasi Member Success',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $aktivasiMember = AktivasiMember::find($id);

        if(is_null($aktivasiMember)){
            return response([
                'message' => 'Aktivasi Member Not Found',
                'data' => null
            ], 404);
        }

        if($aktivasiMember->delete()){
            return response([
                'message' => 'Delete Aktivasi Member Success',
                'data' => $aktivasiMember
            ], 200);
        }

        return response([
            'message' => 'Delete Aktivasi Member Failed',
            'data' => null
        ], 400);
    }
}
