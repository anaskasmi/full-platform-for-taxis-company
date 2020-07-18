<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'driver_shifts';
    protected $guarded=[];
    public $timestamps = false;
    protected $primaryKey = 'driver_shift_id';
     
}
