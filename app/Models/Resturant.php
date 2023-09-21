<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Auth;
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

    public function meals()
    {
        return $this->hasMany('App\Models\Meal');
    }

    public function paymentWays(){

        return $this->belongsToMany(PaymentWay::class,'payment_way_resturant','resturant_id','payment_way_id');
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

    public function getIsOfferedAttribute()
    {
        if(count($this->whereHas('meals',function($q){

            return $q->whereHas('meal_attributes',function($query){

               return $query->whereNotNull('offer_price');
             });
           })->get()
        ))
        {
           return 1;
        }
           return 0;

    }

    public function getIsFavouriteAttribute()
    {
        $fav_resturants=auth()->user()?->whereHas('favResturants',function($q){

            return $q->where('resturant_id',$this->id);

        })->get();
        if(is_countable($fav_resturants) && count($fav_resturants))
        {
           return 1;
        }
           return 0;

    }

    public function getStatusAttribute(){

        if($this->attributes['status'] == 1){

            return 'Opened';
        }
            return 'Closed';

    }


    public function scopeSearch($q,$model){

        $model->when(request('search'),function() use($q){

            return $q->whereTranslationLike('name', '%' . request('search') . '%')
            ->orWhere('description', 'like', '%' . request('search') . '%')
            ->orWhere('address', 'like', '%' . request('search') . '%')

            ->orWhereHas('meals',function($q){

                return $q->whereTranslationLike('name', '%' . request('search') . '%')
                          ->orWhereTranslationLike('description', '%' . request('search') . '%');
            });

        });

    }


    public function scopeFilter($q,$model){

        // sort by

        $model->when(request('sort_by'),function() use($q){

          if(request('sort_by')=='a_to_z'){
               return $q->orderByTranslation('name','ASC');
          }

               return $q->orderBy('delivery_time','ASC');

        // filter by

        })->when(request('filter_by'),function() use($q){
              
            if(in_array('deals',request('filter_by')) && in_array('online_payment',request('filter_by'))){

                return $q->whereHas('meals',function($q){

                    return $q->whereHas('meal_attributes',function($query){

                       return $query->whereNotNull('offer_price');
                    });
                })
                ->WhereHas('paymentWays',function($query){

                    return $query->where('name','visa');
                });



           }
           else if(in_array('deals',request('filter_by'))){

            return $q->whereHas('meals',function($q){
                return $q->whereHas('meal_attributes',function($query){
                   return $query->whereNotNull('offer_price');
                });
            });

           }
           else{

            return $q->whereHas('paymentWays',function($query){

                return $query->where('name','visa');
            });


           }


 

        });

    }

    public function scopeRate($q,$model){

     return $q->Join('reviews','reviews.resturant_id','=','resturants.id')
     ->orderByRaw('ROUND((AVG(reviews.order_packaging) + AVG(reviews.delivery_time) + AVG(reviews.value_of_money)) / 3,1) desc')
     ->groupBy('reviews.resturant_id')
     ->having('reviews.resturant_id',$this->id)
     ->select('reviews.resturant_id');

    }



}