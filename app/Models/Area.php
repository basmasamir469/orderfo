<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Area extends Model implements TranslatableContract
{
    use Translatable;
    
    public $translatedAttributes = ['name'];

    protected $table = 'areas';
    public $timestamps = true;
    protected $guarded=[];


    public function governorate()
    {
        return $this->belongsTo('App\Models\Governorate');
    }

}