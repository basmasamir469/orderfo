<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model  
{

    protected $table = 'category_translations';
    protected $translationForeignKey = 'category_id';
    public $timestamps = false;
    protected $guarded=[];

public function category(){
    return $this->belongsTo(Category::class,'category_id');
}
}