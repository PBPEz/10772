<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Member::all();

        if(count($members) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $members
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
            'nama_member' => 'required',
            'tanggal_lahir' => 'required',
            'email' => 'required|email:rfc,dns',
            'deposit_uang' => 'numeric',
            'deposit_kelas' => 'numeric',
            'nomor_telepon' => 'required|numeric',
            'gender' => 'required',
            'status' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $id = IdGenerator::generate(['table' => 'members', 'length' => 10, 'prefix' => date('y.m.')]);

        $storeData['id'] = $id;
        $storeData['username'] = $storeData['id'];
        $storeData['password'] = $storeData['tanggal_lahir'];

        $member = Member::create($storeData);
        return response([
            'message' => 'Add member Success',
            'data' => $member
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
        $member = Member::find($id);

        if(!is_null($member)){
            return response([
                'message' => 'Retrieve product memberSuccess',
                'data' => $member   
            ], 200);
        }

        return response([
            'message' => 'Member Not Found',
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
        $member = Member::find($id);
        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_member' => 'required',
            'tanggal_lahir' => 'required',
            'email' => 'required|email:rfc,dns',
            'deposit_uang' => 'numeric',
            'deposit_kelas' => 'numeric',
            'nomor_telepon' => 'required',
            'gender' => 'required',
            'status' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $member->nama_member = $updateData['nama_member'];
        $member->tanggal_lahir = $updateData['tanggal_lahir'];
        $member->email = $updateData['email'];
        $member->deposit_uang = $updateData['deposit_uang'];
        $member->deposit_kelas = $updateData['deposit_kelas'];
        $member->nomor_telepon = $updateData['nomor_telepon'];
        $member->gender = $updateData['gender'];
        $member->status = $updateData['status'];
        $member->password = bcrypt($updateData['password']);
        

        if($member->save()){
            return response([
                'message' => 'Update member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Update Member Success',
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
        $member = Member::find($id);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ], 404);
        }

        if($member->delete()){
            return response([
                'message' => 'Delete Member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Delete Member Failed',
            'data' => null
        ], 400);
    }
}
