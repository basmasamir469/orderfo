<?php
use Illuminate\Support\Facades\Route;


Route::group(['namespace'=>'Api\Admin'],function(){

    Route::post('resturants/login','ResturantController@login');
    
    Route::group(['middleware'=>['auth:sanctum','role:resturant'],'prefix'=>'v1'],function(){
        Route::get('resturant/conversations','ConversationController@index');
        Route::get('resturant/conversations/{id}','ConversationController@show');
        Route::post('resturant/messages','ConversationController@sendMessage');
    
    });
    
    Route::group(['prefix'=>'v1','middleware'=>['auth:sanctum','role:admin']],function(){

        Route::get('categories','CategoryController@index')->name('categories.index');
        Route::post('categories','CategoryController@store')->name('categories.store');
        Route::put('categories/{category}','CategoryController@update')->name('categories.update');
        Route::delete('categories/{category}','CategoryController@destroy')->name('categories.destroy');

        Route::get('sliders','SliderController@index')->name('sliders.index');
        Route::get('sliders/{slider}','SliderController@show')->name('sliders.show');
        Route::post('sliders','SliderController@store')->name('sliders.store');
        Route::put('sliders/{slider}','SliderController@update')->name('sliders.update');
        Route::delete('sliders/{slider}','SliderController@destroy')->name('sliders.destroy');

        Route::get('resturants','ResturantController@index')->name('resturants.index');
        Route::get('resturants/{resturant}','ResturantController@show')->name('resturants.show');
        Route::post('resturants','ResturantController@store')->name('resturants.store');
        Route::put('resturants/{resturant}','ResturantController@update')->name('resturants.update');
        Route::delete('resturants/{resturant}','ResturantController@destroy')->name('resturants.destroy');

        Route::get('meals/{resturant_id}','MealController@index')->name('meals.index');
        Route::get('meals/{meal}','MealController@show')->name('meals.show');
        Route::post('meals','MealController@store')->name('meals.store');
        Route::put('meals/{meal}','MealController@update')->name('meals.update');
        Route::delete('meals/{meal}','MealController@destroy')->name('meals.destroy');

        
        Route::get('areas','AreaController@index')->name('areas.index');
        Route::get('areas/{area}','AreaController@show')->name('areas.show');
        Route::post('areas','AreaController@store')->name('areas.store');
        Route::put('areas/{area}','AreaController@update')->name('areas.update');
        Route::delete('areas/{area}','AreaController@destroy')->name('areas.destroy');


        Route::get('orders','OrderController@index')->name('orders.index');
        Route::get('orders/{order}','OrderController@show')->name('orders.show');
        Route::post('orders/accept/{order}','OrderController@acceptOrder')->name('orders.accept');
        Route::post('orders/reject/{order}','OrderController@rejectOrder')->name('orders.reject');
        Route::post('orders/out-for-delivery/{order}','OrderController@outForDelivery')->name('orders.outForDelivery');

        Route::put('settings/about','SettingController@update')->name('settings.update');



    });
});
