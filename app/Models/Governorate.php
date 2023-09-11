<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Governorate extends Model implements TranslatableContract
{
    use Translatable;
    
    public $translatedAttributes = ['name']; 
    protected $table = 'governorates';
    public $timestamps = true;
    protected $guarded=[];


    public function areas()
    {
        return $this->hasMany('App\Models\Area');
    }

}