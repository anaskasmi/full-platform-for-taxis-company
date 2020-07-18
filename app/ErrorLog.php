<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    //
    protected $table = 'errors_log';
    protected $primaryKey = 'id';
    protected $guarded=[];
}
