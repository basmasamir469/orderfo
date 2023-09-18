<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Resturant extends Model implements TranslatableContract,HasMedia
{
    use Translatable,InteractsWithMedia;
    
    public $translatedAttributes = ['name'];
    protected $table = 'resturants';
    public $timestamps = true;
    protected $guarded=[];


    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function sliders()
    {
        return $this->hasMany('App\Models\Slider');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function favByUser()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function odrers()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function getLogoAttribute(){

        return $this->getFirstMediaUrl('resturants-logos');
    }

    public function getImagesAttribute(){

        return $this->getMedia('resturants-images');
    }

    public function getRateAttribute(){
        
        return DB::table('resturants')
        ->leftJoin('reviews','reviews.resturant_id','=','resturants.id')
        ->select(DB::raw("ROUND((AVG(reviews.order_packaging) + AVG(reviews.delivery_time) + AVG(reviews.value_of_money)) / 3,1) AS rate"))
        ->groupBy('resturants.id')
        ->having('resturants.id','=',$this->id)
        ->get();
        //  return $this->whereHas('reviews',function($q){
        //    return round(($q->avg('order_packaging') + $q->avg('delivery_time') + $q->avg('value_of_money')) / count($this->reviews),1);
        //  })->get();
    }


}