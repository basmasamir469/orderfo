<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable,InteractsWithMedia,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'phone',
        'is_active_email',
        'is_active_phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $guard='api';
    
    public function favResturants()
    {
        return $this->belongsToMany('App\Models\Resturant');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function cart()
    {
        return $this->hasOne('App\Models\Cart');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
    public function getProfileImageAttribute()
    {
        return $this->getFirstMediaUrl('users-images');
    }

    public function token(){
        return $this->hasOne('App\Models\Token');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

}
