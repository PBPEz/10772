<?php

use App\Http\Controllers\Api\AktivasiMemberController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingGymController;
use App\Http\Controllers\Api\BookingKelasController;
use App\Http\Controllers\Api\InstrukturController;
use App\Http\Controllers\Api\JadwalUmumController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\MobileController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\PresensiGymController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('pegawai', [PegawaiController::class, 'store']);
Route::post('pegawai/login', [AuthController::class, 'login']);
Route::post('member/login', [MemberController::class, 'login']);
Route::post('instruktur/login', [InstrukturController::class, 'login']);

Route::get('member', [MemberController::class, 'index']);
Route::get('member/{id}', [MemberController::class, 'show']);
Route::post('member', [MemberController::class, 'store']);
Route::put('member/{id}', [MemberController::class, 'update']);
Route::delete('member/{id}', [MemberController::class, 'destroy']);

Route::get('instruktur', [InstrukturController::class, 'index']);
Route::get('instruktur/{id}', [InstrukturController::class, 'show']);
Route::post('instruktur', [InstrukturController::class, 'store']);
Route::put('instruktur/{id}', [InstrukturController::class, 'update']);
Route::delete('instruktur/{id}', [InstrukturController::class, 'destroy']);

Route::get('jadwal', [JadwalUmumController::class, 'index']);
Route::get('jadwal/{id}', [JadwalUmumController::class, 'show']);
Route::post('jadwal', [JadwalUmumController::class, 'store']);
Route::put('jadwal/{id}', [JadwalUmumController::class, 'update']);
Route::delete('jadwal/{id}', [JadwalUmumController::class, 'destroy']);

Route::get('aktivasiMember', [AktivasiMemberController::class, 'index']);
Route::get('aktivasiMember/{id}', [AktivasiMemberController::class, 'show']);
Route::post('aktivasiMember', [AktivasiMemberController::class, 'store']);
Route::put('aktivasiMember/{id}', [AktivasiMemberController::class, 'update']);
Route::delete('aktivasiMember/{id}', [AktivasiMemberController::class, 'destroy']);

Route::get('bookingGym', [BookingGymController::class, 'index']);
Route::get('bookingGym/{id}', [BookingGymController::class, 'show']);
Route::post('bookingGym', [BookingGymController::class, 'store']);
Route::put('bookingGym/{id}', [BookingGymController::class, 'update']);
Route::delete('bookingGym/{id}', [BookingGymController::class, 'destroy']);

Route::get('bookingKelas', [BookingKelasController::class, 'index']);
Route::get('bookingKelas/{id}', [BookingKelasController::class, 'show']);
Route::post('bookingKelas', [BookingKelasController::class, 'store']);
Route::put('bookingKelas/{id}', [BookingKelasController::class, 'update']);