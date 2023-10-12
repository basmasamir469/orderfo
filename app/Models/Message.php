<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    public $timestamps = true;
    protected $guarded=[];

    public function conversation()
    {
        return $this->belongsTo('App\Models\Conversation');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

    public function getDateAttribute()
    {
        return  (Carbon::now()->year == Carbon::parse($this->created_at)->year)? Carbon::parse($this->created_at)->format('M d') : Carbon::parse($this->created_at)->format('Y M d');
    }
}
