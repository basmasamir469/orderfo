<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Resturant extends Model implements TranslatableContract
{
    use Translatable;
    
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

}