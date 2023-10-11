<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model implements TranslatableContract
{   
    use Translatable;
    use HasFactory;
    public $translatedAttributes = ['title','content']; 
    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
