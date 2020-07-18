<?php


namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vehicle as VehicleResource;
use App\Marks;
use App\RotationsCategory;
use App\Vehicle;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    //list all the vehicles
    public function vehicles()
    {
        //get vehicles
        $vehicles = Vehicle::
            select(
            'id',
            'number',
            'type'

        )
            ->orderBy('created_at', 'desc')
            ->get();

        //get all rotation types
        $rotationsCategories = RotationsCategory::
            select(
            'id',
            'name'
        )
            ->get();
        //add to each vehicles its own marks
        foreach ($vehicles as $vehicle) {
           
            $marksByCategory = array();
            
            foreach ($rotationsCategories as $rotationsCategory) {
                //check if there is a mark row
                $markRowExiste = Marks::where('vehicle_id', $vehicle->id)
                    ->where('rotation_category_id', $rotationsCategory->id)
                    ->first();
                if ($markRowExiste) {
                    $marksByCategory [(string)$rotationsCategory->id] = $markRowExiste->marks;
                }
            }
             $vehicle['marksByCategory']=$marksByCategory;

        }


        return $vehicles;
    }

}
