<?php
        use App\Trip;

namespace App;

class MtoTrip {
    public $trip_id;
    public $job_id;
    public $image_path;
    public $date;
    public $vehicle;
    public $badge_id;
    public $customer_name;
    public $pickup;
    public $dropoff;
    public $fare;
    public $tip;
    public $total;
    public $account;
    public $po;
    public $pickup_date;
    public $dropoff__date;
    public $rotation;
    public $dispatcher;
    public $signature_path;

    public function storeThisTrip ()
    {       
            
            // $errors = [];
            $tripCreated = new Trip();
            // dd($this->date);

            $tripCreated->date = date("Y-m-d H:i:s",$this->pickup_date);
            $tripCreated->vehicle =  is_numeric($this->vehicle)?$this->vehicle:null;
            $tripCreated->job_id =  $this->job_id;
            $tripCreated->badge_id =  is_numeric($this->badge_id)?$this->badge_id:null;
            $tripCreated->customer_name =  $this->customer_name;
            $tripCreated->pickup = $this->pickup;
            $tripCreated->dropoff = $this->dropoff;
            $tripCreated->fare =  $this->fare;
            $tripCreated->tip =  $this->tip;
            $tripCreated->total =  $this->total;
            $tripCreated->account = $this->account;
            $tripCreated->po =  $this->po;
            $tripCreated->pickup_date = date('Y-m-d H:i:s',$this->pickup_date);
            
            $tripCreated->dropoff__date =date('Y-m-d H:i:s',$this->dropoff__date); 
            $tripCreated->save();
            // dd($tripCreated);
            return $tripCreated->trip_id;
            // dd($tripCreated);


    }
    /**
     * Get the value of trip_id
     */ 
    public function getTrip_id()
    {
        return $this->trip_id;
    }

    /**
     * Set the value of trip_id
     *
     * @return  self
     */ 
    public function setTrip_id($trip_id)
    {
        $this->trip_id = $trip_id;

        return $this;
    }

    /**
     * Get the value of job_id
     */ 
    public function getJob_id()
    {
        return $this->job_id;
    }

    /**
     * Set the value of job_id
     *
     * @return  self
     */ 
    public function setJob_id($job_id)
    {
        $this->job_id = $job_id;

        return $this;
    }

    /**
     * Get the value of image_path
     */ 
    public function getImage_path()
    {
        return $this->image_path;
    }

    /**
     * Set the value of image_path
     *
     * @return  self
     */ 
    public function setImage_path($image_path)
    {
        $this->image_path = $image_path;

        return $this;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of vehicle
     */ 
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * Set the value of vehicle
     *
     * @return  self
     */ 
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * Get the value of badge_id
     */ 
    public function getBadge_id()
    {
        return $this->badge_id;
    }

    /**
     * Set the value of badge_id
     *
     * @return  self
     */ 
    public function setBadge_id($badge_id)
    {
        $this->badge_id = $badge_id;

        return $this;
    }

    /**
     * Get the value of customer_name
     */ 
    public function getcustomer_name()
    {
        return $this->customer_name;
    }

    /**
     * Set the value of customer_name
     *
     * @return  self
     */ 
    public function setcustomer_name($customer_name)
    {
        $this->customer_name = $customer_name;

        return $this;
    }

    /**
     * Get the value of pickup
     */ 
    public function getPickup()
    {
        return $this->pickup;
    }

    /**
     * Set the value of pickup
     *
     * @return  self
     */ 
    public function setPickup($pickup)
    {
        $this->pickup = $pickup;

        return $this;
    }

    /**
     * Get the value of dropoff
     */ 
    public function getDropoff()
    {
        return $this->dropoff;
    }

    /**
     * Set the value of dropoff
     *
     * @return  self
     */ 
    public function setDropoff($dropoff)
    {
        $this->dropoff = $dropoff;

        return $this;
    }

    /**
     * Get the value of fare
     */ 
    public function getFare()
    {
        return $this->fare;
    }

    /**
     * Set the value of fare
     *
     * @return  self
     */ 
    public function setFare($fare)
    {
        $this->fare = $fare;

        return $this;
    }

    /**
     * Get the value of tip
     */ 
    public function getTip()
    {
        return $this->tip;
    }

    /**
     * Set the value of tip
     *
     * @return  self
     */ 
    public function setTip($tip)
    {
        $this->tip = $tip;

        return $this;
    }

    /**
     * Get the value of total
     */ 
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the value of total
     *
     * @return  self
     */ 
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the value of account
     */ 
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set the value of account
     *
     * @return  self
     */ 
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get the value of po
     */ 
    public function getPo()
    {
        return $this->po;
    }

    /**
     * Set the value of po
     *
     * @return  self
     */ 
    public function setPo($po)
    {
        $this->po = $po;

        return $this;
    }

    /**
     * Get the value of pickup_date
     */ 
    public function getPickup_date()
    {
        return $this->pickup_date;
    }

    /**
     * Set the value of pickup_date
     *
     * @return  self
     */ 
    public function setPickup_date($pickup_date)
    {
        $this->pickup_date = $pickup_date;

        return $this;
    }

    /**
     * Get the value of dropoff__date
     */ 
    public function getDropoff__date()
    {
        return $this->dropoff__date;
    }

    /**
     * Set the value of dropoff__date
     *
     * @return  self
     */ 
    public function setDropoff__date($dropoff__date)
    {
        $this->dropoff__date = $dropoff__date;

        return $this;
    }

    /**
     * Get the value of rotation
     */ 
    public function getRotation()
    {
        return $this->rotation;
    }

    /**
     * Set the value of rotation
     *
     * @return  self
     */ 
    public function setRotation($rotation)
    {
        $this->rotation = $rotation;

        return $this;
    }

    /**
     * Get the value of dispatcher
     */ 
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Set the value of dispatcher
     *
     * @return  self
     */ 
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Get the value of signature_path
     */ 
    public function getSignature_path()
    {
        return $this->signature_path;
    }

    /**
     * Set the value of signature_path
     *
     * @return  self
     */ 
    public function setSignature_path($signature_path)
    {
        $this->signature_path = $signature_path;

        return $this;
    }
}
