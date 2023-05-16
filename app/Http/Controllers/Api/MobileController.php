<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use App\Models\Instruktur;

class MobileController extends Controller
{
    public function login(Request $request){
        $request->validate( [
            'username' => 'required',
            'password' => 'required'
        ]);

        if(Member::where('nama_member',$request->username)->first())
        {
            $login = Member::where('nama_member',$request->username)->first();
            
            if(Hash::check($request->password, $login['password'])){
                $member = Member::where('nama_member',$request->username)->first();
            }
            else{
                return response([
                    'message' => 'Password Member Salah!'
                ], 400);
            }
            return response([
                'message' => 'Login member berhasil!',
                'data' => $member,
            ]);
        }
        else if(Instruktur::where('nama_instruktur',$request->username)->first())
        {
            $login = Instruktur::where('nama_instruktur',$request->username)->first();
            
            if(Hash::check($request->password, $login['password'])){
                $instruktur = Instruktur::where('nama_instruktur',$request->username)->first();
            }
            else{
                return response([
                    'message' => 'Password Instruktur Salah!'
                ], 400);
            }
            return response([
                'message' => 'Login instruktur berhasil!',
                'data' => $instruktur,
            ]);
        }
        else{
            return response([
                'message' => 'Username Salah!'
            ], 400);
        }
    }
}
