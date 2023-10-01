<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded=[];
    
    Const TYPES=[
          'text',
          'file',
          'textarea',
          'number'
    ];
    protected $casts = ['value' => 'array'];

    public static function map(): array
    {
        return static::all()->mapWithKeys(fn ($i) => [$i->key => $i])->all();
    }
}
