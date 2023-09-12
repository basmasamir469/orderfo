<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivationProcess extends Model 
{

    protected $table = 'activation_processes';
    public $timestamps = true;
    protected $guarded=[];

    /**
     * status
     * 
     * 0 --> pnding
     * 1 --> verified
     */

}