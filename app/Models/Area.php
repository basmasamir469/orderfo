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

    public function scopeSearch($q){

        self::query()->when(request('search'),function() use($q){

            return $q->whereTranslationLike('name', '%' . request('search') . '%')

            ->orWhereHas('governorate',function($q){

                return $q->whereTranslationLike('name', '%' . request('search') . '%');
            });

        });

    }

    public function scopeFilter($q)
    {
      $q->when(request('governorate_id'),function() use($q){

        return $q->whereHas('governorate',function($query){

            return $query->where('id',request('governorate_id'));
        });

      });
    }


}