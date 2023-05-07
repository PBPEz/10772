<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_pegawai' => 'required',
            'tanggal_lahir' => 'required',
            'email' => 'required|email',
            'nomor_telepon' => 'required',
            'role' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $storeData['password'] = bcrypt($storeData['password']);
        $pegawai = Pegawai::create($storeData);

        $tokenResult = $pegawai->createToken('token')->accessToken;

        $data= [
            'access_token' => $tokenResult, 
            'token_type' => 'Bearer',
            'pegawai' => $pegawai
        ];

        return response([
            'message' => 'Add Pegawai Success',
            'data' => $data
        ],200);
    }
}
