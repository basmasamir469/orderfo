<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model 
{

    protected $table = 'cart_items';
    public $timestamps = true;
    protected $guarded=[];


    public function cart()
    {
        return $this->belongsTo('App\Models\Cart');
    }

    public function meal()
    {
        return $this->hasOne('App\Models\Meal');
    }

}