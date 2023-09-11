<?php

use App\Http\Controllers\Api\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace'=>'Api'],function(){
    Route::group(['prefix'=>'users/auth'],function(){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/verify',[AuthController::class,'sendVerifyCode'])->name('users.verifyemail');
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/forget-password',[AuthController::class,'forgetPassword'])->name('users.forgetpassword');
    Route::post('/reset-password/code',[AuthController::class,'sendResetPasswordCode']);
    Route::post('/reset-password',[AuthController::class,'resetPassword'])->name('users.resetpassword');
});
Route::group(['middleware'=>'auth:sanctum','prefix'=>'users'],function(){
Route::get('/profile',[AuthController::class,'getProfile'])->name('users.profile');
Route::put('/profile/update',[AuthController::class,'updateProfile'])->name('users.updateProfile');
Route::post('/logout',[AuthController::class,'logout']);
Route::post('/profile/image',[AuthController::class,'uploadImage'])->name('users.imageProfile');

});
});
