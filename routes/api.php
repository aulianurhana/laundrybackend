<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['jwt.verify:admin,kasir,owner']], function() {
    Route::post('login/check', [AuthController::class, 'loginCheck']);
    Route::post('logout', [AuthController::class, 'logout']);    
});
