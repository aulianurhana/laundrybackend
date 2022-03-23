<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
// use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\DashboardController;

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

Route::post('user/tambah', [UserController::class, 'store']);
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ['jwt.verify:admin,kasir,owner']], function () {
    Route::get('login/check', [UserController::class, 'loginCheck']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('tampiluser', [UserController::class, 'getAll']);
    Route::get('dashboard', [DashboardController::class, 'index']);
    // Report
    Route::post('report', [TransaksiController::class, 'report']);
});

// route khusus admin

Route::group(['middleware' => ['jwt.verify:admin']], function () {

    // outlet
    Route::post('tambahoutlet', [OutletController::class, 'store']);
    Route::get('tampiloutlet', [OutletController::class, 'getAll']);
    Route::get('tampiloutlet/{id}', [OutletController::class, 'getById']);
    Route::put('updateoutlet/{id}', [OutletController::class, 'update']);
    Route::delete('deleteoutlet/{id}', [OutletController::class, 'delete']);

    // paket
    Route::post('tambahpaket', [PaketController::class, 'store']);
    Route::get('tampilpaket', [PaketController::class, 'getAll']);
    Route::get('tampilpaket/{id}', [PaketController::class, 'getdata']);
    Route::put('updatepaket/{id}', [PaketController::class, 'update']);
    Route::delete('deletepaket/{id}', [PaketController::class, 'delete']);

    //USER
    Route::post('tambahuser', [UserController::class, 'store']);
    Route::get('tampiluser', [UserController::class, 'getAll']);
    Route::get('tampiluser/{id}', [UserController::class, 'getById']);
    Route::put('updateuser/{id}', [UserController::class, 'update']);
    Route::delete('deleteuser/{id}', [UserController::class, 'delete']);
});

// route khusus admin

Route::group(['middleware' => ['jwt.verify:admin,kasir']], function () {
     // member
     Route::post('tambahmember', [MemberController::class, 'store']);
     Route::get('tampilmember', [MemberController::class, 'getAll']);
     Route::get('tampilmember/{id}', [MemberController::class, 'getdata']);
     Route::put('updatemember/{id}', [MemberController::class, 'update']);
     Route::delete('deletemember/{id}', [MemberController::class, 'delete']);
 
 
 
     // Transaksi dan Detail transaksi
     Route::post('tambahtransaksi', [TransaksiController::class, 'store']);
     Route::get('tampiltransaksi', [TransaksiController::class, 'getAll']);
     Route::get('tampiltransaksi/{id}', [TransaksiController::class, 'getbyid']);
     Route::post('transaksi/status/{id}', [TransaksiController::class, 'changeStatus']);
     Route::post('transaksi/bayar/{id}', [TransaksiController::class, 'bayar']);
     Route::post('transaksi/detail/tambah', [DetailTransaksiController::class, 'store']);
     Route::get('transaksi/detail/{id}', [DetailTransaksiController::class, 'getById']);
     Route::get('transaksi/total/{id}', [DetailTransaksiController::class, 'getTotal']);
     Route::get('tampildetail/{id}', [DetailTransaksiController::class, 'getById']);
     Route::get('tampildetail', [DetailTransaksiController::class, 'getAll']);
});