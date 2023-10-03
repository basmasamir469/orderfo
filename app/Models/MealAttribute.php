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

    /*
    type 

    0 => individual
    
    1 => sharing_box

    */
    public function meal()
    {
        return $this->belongsTo('App\Models\Meal');
    }

    public function getTypeAttribute(){

        if($this->attributes['type'] == 0){

            return 'size';
        }
        else if($this->attributes['type'] == 1)
        {
            return 'extras';
        }

        else
        {
            return 'options';
        }

    }


}