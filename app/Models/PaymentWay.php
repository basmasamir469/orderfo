<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentWay extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function resturants(){
        return $this->belongsToMany(Resturant::class,'payment_way_resturant','payment_way_id','resturant_id');
    }
}
