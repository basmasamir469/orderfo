<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResturantTranslation extends Model 
{

    protected $table = 'resturant_translations';
    protected $translationForeignKey = 'resturant_id';
    public $timestamps = false;
    protected $guarded=[];


}