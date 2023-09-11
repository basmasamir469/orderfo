<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class MealAttribute extends Model implements TranslatableContract
{
    use Translatable;
    
    public $translatedAttributes = ['name']; 
    protected $table = 'meal_attributes';
    public $timestamps = true;
    protected $guarded=[];


    public function meal()
    {
        return $this->belongsTo('App\Models\Meal');
    }

}