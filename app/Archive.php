<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    //
    protected $table = 'archives';
    protected $fillable = [
        'badge_id', 'LICENSE_PATH', 'PERMIT_PATH', 'TAXIHOST_PATH', 'ABSTRACT_PATH', 'SP_FILE_PATH',
    ];
    protected $primaryKey = 'archive_id';
    protected $guarded=[];
}
