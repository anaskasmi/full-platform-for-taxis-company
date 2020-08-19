<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreInspection extends Model
{
    //
    protected $table = 'pre_inspection_slips';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public function vehicle()
    {
        return $this->hasOne('App\Vehicle', 'id','VehicleId');
    }
    public function scopeWithAll($query)
    {
        $query->with('vehicle');

    }

}
