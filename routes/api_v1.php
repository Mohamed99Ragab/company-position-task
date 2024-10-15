<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\VerifyAccount;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\StatsController;
use App\Http\Controllers\Api\V1\TagController;
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



Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])
    ->middleware('auth:sanctum');

Route::post('verify-account',[VerifyAccount::class,'verifyAccount']);
Route::post('resend-otp',[VerifyAccount::class,'resendOtp']);



Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/user',function (Request $request){
        return $request->user();
    });


    Route::apiResource('tags',TagController::class);

    Route::group(['prefix'=>'posts','as'=>'posts.','controller'=>PostController::class],function (){
        Route::get('deleted','postsIsDeleted')->name('deleted');
        Route::post('restore/{id}','restorePosts')->name('restore');


        Route::get('/','index')->name('index');
        Route::get('/{id}','show')->name('show');
        Route::post('/','store')->name('store');
        Route::post('/{id}','update')->name('update');
        Route::delete('/{id}','destroy')->name('delete');




    });

    Route::get('stats',[StatsController::class,'index']);


});
