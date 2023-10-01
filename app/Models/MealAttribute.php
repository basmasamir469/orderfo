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
    // protected $casts = [
    //     'size' => 'array',
    //     'option'=>'array',
    //     'extras'=>'array'
    // ];

    /*
    type 

    0 => individual
    
    1 => sharing_box

    */
    public function meal()
    {
        return $this->belongsTo('App\Models\Meal');
    }

    public function setSizeAttribute($size)
    {
        $this->attributes['size']=json_encode($size);
    }
  
    public function setOptionAttribute($option)
    {
           $this->attributes['option']=json_encode($option);
    } 
        
    public function setExtrasAttribute($extras)
    {
          $this->attributes['extras']=json_encode($extras);
    } 
    
    public function getSizeAttribute()
    {
       return json_decode($this->attributes['size'],true);
    }
  
    public function getOptionAttribute()
    {
        return json_decode($this->attributes['option'],true);
    } 
        
    public function getExtrasAttribute()
    {
        return json_decode($this->attributes['extras'],true);

    }    

}