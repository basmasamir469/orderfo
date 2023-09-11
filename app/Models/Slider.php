<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Slider extends Model implements TranslatableContract,HasMedia
{
    use Translatable;
    use InteractsWithMedia;

    
    public $translatedAttributes = ['text']; 

    protected $table = 'sliders';
    public $timestamps = true;
    protected $guarded=[];


    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

    public function getImageAttribute(){
        return $this->getFirstMediaUrl('sliders-images');
    }

}