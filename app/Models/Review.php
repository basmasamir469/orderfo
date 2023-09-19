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
        return $this->belongsTo('App\Models\Resturant','resturant_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}