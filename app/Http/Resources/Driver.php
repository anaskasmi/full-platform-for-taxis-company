<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Driver extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->PermitExpiry=date("Y-m-d", strtotime($this->PermitExpiry));
        $this->LicenseExpiry=date("Y-m-d", strtotime($this->LicenseExpiry));
        $this->AbstractExpiry=date("Y-m-d", strtotime($this->AbstractExpiry));
        $this->START_DATE=date("Y-m-d", strtotime($this->START_DATE));
        $this->END_DATE=date("Y-m-d", strtotime($this->END_DATE));
        $this->created_at=date("Y-m-d", strtotime($this->created_at));
        $this->updated_at=date("Y-m-d", strtotime($this->updated_at));
        // $news= $this->START_DATE->format('Y/m/d');
        // return parent::toArray($request);
        return [

            "PermitNumber"=>$this->PermitNumber,
            "id"=>$this->id,
            "Name"=>$this->Name,
            "FirstName"=>$this->FirstName,
            "LastName"=>$this->LastName,
            "image"=>$this->image,
            "LicenseNumber"=>$this->LicenseNumber,
            "LicenseExpiry"=>$this->LicenseExpiry,
            "PermitExpiry"=>$this->PermitExpiry,
            "AbstractExpiry"=>$this->AbstractExpiry,
            "HomePhone"=>$this->HomePhone,
            "TAXI_HOST"=>$this->TAXI_HOST,
            "ADDRESS"=>$this->ADDRESS,
            "email"=>$this->email,
            "START_DATE"=>$this->START_DATE,
            "END_DATE"=>$this->END_DATE,
            "DRIVER_STATUS"=>$this->DRIVER_STATUS,
            "LICENSE_CLASS"=>$this->LICENSE_CLASS,
            "LICENSE_PATH"=>$this->LICENSE_PATH,
            "PERMIT_PATH"=>$this->PERMIT_PATH,
            "TAXIHOST_PATH"=>$this->TAXIHOST_PATH,
            "ABSTRACT_PATH"=>$this->ABSTRACT_PATH,
            "SP_FILE_PATH"=>$this->SP_FILE_PATH,
            "created_at"=>$this->created_at,
            "updated_at"=>$this->updated_at,
        ];
    }
}
