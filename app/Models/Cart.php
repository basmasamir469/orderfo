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

    public function cartItems()
    {
        return $this->hasMany('App\Models\CartItem');
    }

}