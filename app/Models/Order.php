<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $guarded=[];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public function meals()
    {
        return $this->belongsToMany('App\Models\Meal');
    }

}