<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $table = 'piccolo_trips';
    protected $guarded=[];
    public $timestamps = false;
    protected $primaryKey = 'trip_id';

    public function getfareAttribute($fare)
    {
        return number_format(($fare /100), 2, '.', ' ');
    }
    public function gettipAttribute($tip)
    {
        return number_format(($tip /100), 2, '.', ' ');
    }
    public function gettotalAttribute($total)
    {
        return number_format(($total /100), 2, '.', ' ');
    }
     
}
