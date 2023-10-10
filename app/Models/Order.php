<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $guarded=[];

    Const CANCELLED      = 0;
    Const PENDING        = 1;
    Const APPROVED       = 2;
    Const OUTFORDELIVERY = 3;
    Const DELIVERED      = 4;


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public function promotion()
    {
       return $this->hasOne('App\Models\Promotion','promo_code');
    }


    public function meals()
    {
        return $this->belongsToMany('App\Models\Meal')->withPivot(['quantity','meal_price','size','option','extras','special_instructions']);
    }

    public function getStatusAttribute()
    {

        if($this->attributes['order_status'] == self::PENDING){

           return 'Order is waiting to be approved';
        }
        else if($this->attributes['order_status'] == self::APPROVED){

            return 'Order is Cooking Now';
         }
        else if($this->attributes['order_status'] == self::OUTFORDELIVERY){

            return 'Order is Out for delivery';
         }
        else if($this->attributes['order_status'] == self::DELIVERED){

            return 'Order has been Delivered';
         }
        else {

            return 'Order is Cancelled';
         }
 
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d,Y') ." at ".Carbon::parse($this->attributes['created_at'])->format('h:i A');
    }

    public function scopeFilter($q)
    {
        return $q->when(request('status'),function () use($q)
        {
           if(request('status') == 'pending'){
              
            return $q->where('order_status',self::PENDING);

           }

           if(request('status') == 'approved'){
              
            return $q->where('order_status',self::APPROVED);

           }

           if(request('status') == 'out_for_delivery'){
              
            return $q->where('order_status',self::OUTFORDELIVERY);

           }

           if(request('status') == 'delivered'){
              
            return $q->where('order_status',self::DELIVERED);

           }



        })->when(request('filter_by'),function() use($q){
          
            if(request('filter_by') == 'current')
            {
                return $q->whereIn('order_status',[self::PENDING,self::APPROVED,self::OUTFORDELIVERY]);
            }
            
            if(request('filter_by') == 'previous')
            {
                return $q->whereIn('order_status',[self::CANCELLED,self::DELIVERED]);
            }



        });
    }

}