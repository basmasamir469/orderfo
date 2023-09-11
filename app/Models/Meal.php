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


    public function meal_attributes()
    {
        return $this->hasMany('App\Models\MealAttribute');
    }

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

    public function cartItem()
    {
        return $this->belongsTo('App\Models\CartItem');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order');
    }

    public function getImageAttribute(){
        return $this->getFirstMediaUrl('meals-images');
    }

}