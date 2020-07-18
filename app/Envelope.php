<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envelope extends Model
{
    protected $table = 'envelopes';
    protected $guarded=[];
    protected $primaryKey = 'id';
     
}
