<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Meal extends Model implements TranslatableContract,HasMedia
{
    use Translatable;
    use InteractsWithMedia;

    
    public $translatedAttributes = ['name','description']; 
    protected $table = 'meals';
    public $timestamps = true;
    protected $guarded=[];

        /*
    type 

    0 => individual
    
    1 => sharing_box

    */

     public function meal_attributes()
     {
         return $this->hasMany('App\Models\MealAttribute');
     }

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

    // public function cartItem()
    // {
    //     return $this->belongsTo('App\Models\CartItem');
    // }

    public function carts()
    {

        return $this->belongsToMany('App\Models\Cart','cart_meal','meal_id','cart_id')->using(CartMeal::class)
        ->withPivot('quantity','size','extras','option','meal_price','special_instructions');

    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order');
    }
    public function getSizeAttribute(){

        return json_decode($this->pivot->size,true);
   
      }   

   public function getExtrasAttribute(){

     return  json_decode($this->pivot->extras,true);

   }

   public function getOptionAttribute(){

    return  json_decode($this->pivot->option,true);

  }


    public function scopeFilter($q)
    {

        return $q->when(request('filter_by'),function() use($q){

          if(request('filter_by')=='top_offers'){
            return $q->whereHas('meal_attributes',function($query){

                return $query->whereNotNull('offer_price');
             });

          }
          else if(request('filter_by') == 'most_selling'){

             return self::Join('meal_order','meals.id','=','meal_order.meal_id')
                        ->select('meal_order.meal_id','meals.*')
                        ->groupBy('meal_order.meal_id')
                        ->selectRaw('COUNT("meal_order.order_id") as orders_count')
                        ->orderBy('orders_count','desc');

          }
          elseif(request('filter_by')=='sharing_box'){

               return $q->where('type',1);
          }

        });

    }

    public function getTypeAttribute(){

        if($this->attributes['type'] == 0){

            return 'individual';
        }
        else if($this->attributes['type'] == 1)
        {
            return 'sharing_box';
        }
    }


}