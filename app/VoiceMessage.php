<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoiceMessage extends Model
{
    //
    protected $table = 'voice_messages';
    protected $primaryKey = 'id';
    protected $guarded=[];
    // public $id;
    // public $duration_in_sec;
    // public $owner_id;
    // public $owner_full_name;
    // public $file_path;
}
