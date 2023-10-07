<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model 
{

    protected $table = 'carts';
    public $timestamps = true;
    protected $guarded=[];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    // public function cartItems()
    // {
    //     return $this->hasMany('App\Models\CartItem');
    // }

    public function meals()
    {
        return $this->belongsToMany('App\Models\Meal','cart_meal','cart_id','meal_id')->using(CartMeal::class)
        ->withPivot('quantity','size','extras','option','meal_price','special_instructions');
    }

}