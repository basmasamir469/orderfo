<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model 
{

    protected $table = 'reviews';
    public $timestamps = true;
    protected $guarded=[];


    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }


}