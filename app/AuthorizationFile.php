<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorizationFile extends Model
{
    //
    protected $table = 'authorization_files';
    protected $primaryKey = 'id';
    protected $guarded=[];
}
