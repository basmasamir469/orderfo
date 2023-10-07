<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartMeal extends Pivot
{

    // protected $table = 'cart_items';
    // public $timestamps = true;
    // protected $guarded=[];

  public function getSizeAttribute(){
     return  json_decode($this->attributes['size'],true);
     }
   public function getExtrasAttribute(){
     return  json_decode($this->attributes['extras'],true);
     }

   public function getOptionAttribute(){
     return  json_decode($this->attributes['option'],true);
    }
 
    // public function cart()
    // {
    //     return $this->belongsTo('App\Models\Cart');
    // }

    // public function meal()
    // {
    //     return $this->hasOne('App\Models\Meal');
    // }

}