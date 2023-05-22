<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InstrukturResource;
use App\Models\Instruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InstrukturController extends Controller
{
    public function login(Request $request){
        $request->validate( [
            'username' => 'required',
            'password' => 'required'
        ]);

        if(Instruktur::where('nama_instruktur',$request->username)->first())
        {
            $login = Instruktur::where('nama_instruktur',$request->username)->first();
            
            if(Hash::check($request->password, $login['password'])){
                $instruktur = Instruktur::where('nama_instruktur',$request->username)->first();
            }
            else{
                return response([
                    'message' => 'Password Salah!'
                ], 400);
            }

            return response([
                'message' => 'Login berhasil!',
                'data' => $instruktur,
            ]);
        }else{
            return response([
                'message' => 'Username Salah!'
            ], 400);
        }
    }
    public function index()
    {
        $instrukturs = Instruktur::all();

        if (count($instrukturs) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $instrukturs
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
            'nama_instruktur' => 'required',
            'tanggal_lahir' => 'required',
            'nomor_telepon' => 'required|numeric',
            'email' => 'required|email:rfc,dns',
            'status' => 'required',
            'gender' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        if ($validator->fails())
            return response(['message' => $validator->errors()], 400);

        $storeData['password'] = bcrypt($storeData['password']);
        $instruktur = Instruktur::create($storeData);
        return response([
            'message' => 'Add Instruktur Success',
            'data' => $instruktur
        ], 200);
    }

    public function show($id)
    {
        $instruktur = Instruktur::find($id);

        if (!is_null($instruktur)) {
            return response([
                'message' => 'Retrieve instruktur Success',
                'data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Instruktur Not Found',
            'data' => null
        ], 404);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $instruktur = Instruktur::find($id);
        if (is_null($instruktur)) {
            return response([
                'message' => 'Instruktur Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_instruktur' => 'required',
            'tanggal_lahir' => 'required',
            'nomor_telepon' => 'required|numeric',
            'email' => 'required',
            'status' => 'required',
            'gender' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $instruktur->nama_instruktur = $updateData['nama_instruktur'];
        $instruktur->tanggal_lahir = $updateData['tanggal_lahir'];
        $instruktur->nomor_telepon = $updateData['nomor_telepon'];
        $instruktur->email = $updateData['email'];
        $instruktur->status = $updateData['status'];
        $instruktur->gender = $updateData['gender'];
        $instruktur->password = $updateData['password'];

        if ($instruktur->save()) {
            return response([
                'message' => 'Update instruktur Success',
                'data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Update Instruktur Failed',
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
        $instruktur = Instruktur::find($id);

        if (is_null($instruktur)) {
            return response([
                'message' => 'Instruktur Not Found',
                'data' => null
            ], 404);
        }

        if ($instruktur->delete()) {
            return response([
                'message' => 'Delete Instruktur Success',
                'data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Delete Instruktur Failed',
            'data' => null
        ], 400);
    }
}