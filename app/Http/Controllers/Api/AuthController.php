<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Pegawai;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate( [
            'nama_pegawai' => 'required',
            'password' => 'required'
        ]);

        if(Pegawai::where('nama_pegawai',$request->nama_pegawai)->first())
        {
            $login = Pegawai::where('nama_pegawai',$request->nama_pegawai)->first();

            if(Hash::check($request->password, $login['password'])){
                $pegawai = Pegawai::where('nama_pegawai',$request->nama_pegawai)->first();
            }
            else{
                return response([
                    'message' => 'Username atau Password salah'
                ], 400);
            }
            $token = $pegawai->createToken('token')->accessToken;
            return response([
                'message' => 'Login berhasil!',
                'data' => $pegawai,
                'token' => $token
            ]);
        }else{
            return response([
                'message' => 'Unauthenticated'
            ], 400);
        }
    }
    public function authentication($id){
        $pegawai = Pegawai::find($id);

        if ($pegawai->role=="Instruktur") {
            return response([
                'message' => 'Retrieve instruktur Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Instruktur Not Found',
            'data' => null
        ], 404);
    }
}
