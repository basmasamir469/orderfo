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
    Route::group(['prefix'=>'v1'],function(){

        Route::post('register',[AuthController::class,'register']);
        Route::post('verify',[AuthController::class,'verifyUser'])->name('users.verifyemail');
        Route::post('login',[AuthController::class,'login']);
        Route::post('forget-password',[AuthController::class,'forgetPassword'])->name('users.forgetpassword');
        Route::post('reset-password/code',[AuthController::class,'checkResetPasswordCode']);
        Route::post('reset-password',[AuthController::class,'resetPassword'])->name('users.resetpassword');

        Route::get('home','HomeController@home');                
        Route::get('offers','HomeController@offers');   

        Route::get('resturants','RestaurantController@index');    
        Route::get('resturants/{id}','RestaurantController@show');
        
        Route::get('meals','MealController@index');    
        Route::get('meals/{id}','MealController@show'); 

        Route::get('resturants/{resturant_id}/reviews','RestaurantController@reviews');

        Route::get('settings/about','HomeController@about');

        Route::get('areas','AddressController@areas');   

        


        Route::group(['middleware'=>'auth:sanctum'],function(){
             
            Route::group(['middleware' => ['role:user']], function () {
                //
                Route::get('profile',[AuthController::class,'getProfile'])->name('users.profile');
                Route::put('profile/update',[AuthController::class,'updateProfile'])->name('users.updateProfile');
                Route::post('logout',[AuthController::class,'logout']);
                Route::post('profile/image',[AuthController::class,'uploadImage'])->name('users.imageProfile');

                Route::get('resturants/add-fav/{Id}','RestaurantController@addToFav');
                Route::get('myfavourite','RestaurantController@favResturants');
                Route::post('resturants/{resturant_id}/reviews','RestaurantController@makeReview');

                Route::get('addresses','AddressController@index');
                Route::post('addresses','AddressController@store');
                Route::put('addresses/{address}','AddressController@update');
                Route::delete('addresses/{address}','AddressController@destroy');                                                    
                
            });    
    
        });        

    });
});
