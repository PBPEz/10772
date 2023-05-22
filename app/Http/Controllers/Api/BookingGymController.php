<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\BookingGym;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\DB;

class BookingGymController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookingGyms = DB::table('booking_gyms')->join('members', 'booking_gyms.id_member','=','members.id')
        ->select('booking_gyms.*','members.nama_member')
        ->get();

        if(count($bookingGyms) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bookingGyms
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
            'tanggal' => '',
            'sesi' => 'required',
            'jam_booking' => ''
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $id = IdGenerator::generate(['table' => 'booking_gyms', 'length' => 10, 'prefix' => date('y.m.')]);

        $storeData['id'] = $id;

        $bookingGym = BookingGym::create($storeData);
        return response([
            'message' => 'Add booking gym Success',
            'data' => $bookingGym,
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
        $bookingGym = BookingGym::find($id);

        if(!is_null($bookingGym)){
            return response([
                'message' => 'Retrieve booking gym Success',
                'data' => $bookingGym   
            ], 200);
        }

        return response([
            'message' => 'Booking gym Not Found',
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
        $bookingGym = BookingGym::find($id);
        if(is_null($bookingGym)){
            return response([
                'message' => 'Booking gym Not Found',
                'data' => null
            ], 404);
        }
    
        if($bookingGym->jam_booking != NULL){
            return response([
                'message' => 'Member sudah presensi!',
                'data' => 'Member sudah presensi!'
            ], 200);
        }

        $bookingGym->jam_booking = date('H:i:s');

        if($bookingGym->save()){
            return response([
                'message' => 'Update Booking Gym Success',
                'data' => $bookingGym
            ], 200);
        }

        return response([
            'message' => 'Update Presensi gym Failed',
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
        $bookingGym = BookingGym::find($id);

        if(is_null($bookingGym)){
            return response([
                'message' => 'Booking gym Not Found',
                'data' => null
            ], 404);
        }

        if($bookingGym->delete()){
            return response([
                'message' => 'Delete Booking gym Success',
                'data' => $bookingGym
            ], 200);
        }

        return response([
            'message' => 'Delete Booking gym Failed',
            'data' => null
        ], 400);
    }
}
