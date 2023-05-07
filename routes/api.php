<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InstrukturController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\PegawaiController;
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