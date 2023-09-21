<?php
use Illuminate\Support\Facades\Route;


Route::group(['namespace'=>'Admin'],function(){

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





    });
});
