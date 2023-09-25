<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model 
{

    protected $table = 'addresses';
    public $timestamps = true;
    protected $guarded=[];

    /**
     * type
     * 0 -> other 
     * 1 -> home
     * 2 -> work
     */
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

}