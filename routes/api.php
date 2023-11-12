<?php

use App\Http\Controllers\KhoaDaoTaoController;
use App\Http\Controllers\KhoaHocController;
use App\Http\Controllers\NienKhoaController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\AuthController;
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
Route::group([],function() {
    Route::group(['prefix'  => '/nien-khoa'], function() {
         Route::get('/data', [NienKhoaController::class, 'getData']);
         Route::post('/create', [NienKhoaController::class, 'createData']);
         Route::post('/update', [NienKhoaController::class, 'updateData']);
         Route::post('/delete', [NienKhoaController::class, 'deleteData']);
         Route::post('/search', [NienKhoaController::class, 'searchData']);
    });
    Route::group(['prefix'  => '/sinh-vien'], function() {
        Route::get('/data', [SinhVienController::class, 'getData']);
        // Route::get('/data/{id}', [SinhVienController::class, 'getDataid']);
        Route::post('/create', [SinhVienController::class, 'createData']);
        Route::post('/update', [SinhVienController::class, 'updateData']);
    });
    Route::group(['prefix' => '/giang-vien'], function(){
        Route::get('/data', [GiangVienController::class,'getData']);
        Route::post('/create', [GiangVienController::class,'createData']);
        Route::post('/update',[GiangVienController::class,'updateData']);
    });
    
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/update', [AuthController::class, 'update']);  
    Route::get('/profilelist', [AuthController::class, 'profilelist']);   
});

