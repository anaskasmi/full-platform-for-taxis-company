<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MLD extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->created_at=date("Y-m-d", strtotime($this->created_at));
        $this->updated_at=date("Y-m-d", strtotime($this->updated_at));
        return [

            "BadgeID"=>$this->BadgeID,
            "id"=>$this->id,
            "Name"=>$this->name,
            "car"=>$this->car,
            "FirstName"=>$this->FirstName,
            "LastName"=>$this->LastName,
            "image"=>$this->image,
            "email"=>$this->email,
            "MLD_STATUS"=>$this->MLD_STATUS,
            "created_at"=>$this->created_at,
            "updated_at"=>$this->updated_at,
        ];
    }
}
