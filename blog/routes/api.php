<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'post'], function(){
        Route::get('/',[APIController::class,'index']);
        Route::post('/',[APIController::class,'store']);
        Route::patch('/{post}',[APIController::class,'update']);
        Route::delete('/{post}',[APIController::class,'destroy']);
    });
});

Route::post('login',[APIController::class,'login']);
