<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements TranslatableContract,HasMedia
{
    use Translatable;
    use InteractsWithMedia,HasFactory;

    
    public $translatedAttributes = ['name'];
    protected $translationForeignKey = 'category_id';
    protected $table = 'categories';
    public $timestamps = true;
    protected $guarded=[];


    public function resturants()
    {
        return $this->hasMany('App\Models\Resturant');
    }

    public function getLogoAttribute(){
        return $this->getFirstMediaUrl('categories-logos');
    }

}