<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeSearch($q)
    {
        return $q->when(request('search'),function() use($q){
            $q->whereHas('resturant',function($q){
                return $q->whereTranslationLike('name', '%' . request('search') . '%');
            });

        });
    }

    public function getLastMessageDateAttribute()
    {
        $last_message = $this->messages()->latest()->first();
        $date = (Carbon::now()->year == Carbon::parse($last_message->created_at)->year)? Carbon::parse($last_message->created_at)->format('M d') : Carbon::parse($last_message->created_at)->format('Y M d');
        $time = Carbon::parse($last_message->created_at)->format('h:i A');
       return  (Carbon::parse($last_message->created_at)->format('M d') == Carbon::today()->format('M d'))? $time : $date;

    }

}
