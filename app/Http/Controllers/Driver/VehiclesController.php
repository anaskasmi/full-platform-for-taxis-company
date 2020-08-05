<?php


namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vehicle as VehicleResource;
use App\Marks;
use App\RotationsCategory;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class VehiclesController extends Controller
{
    //list all the vehicles with names
    public function vehiclesWithNames()
    {

        //get vehicles
        $vehicles = Vehicle::
        select(
            DB::raw("CONCAT(type, ' ', number)   as name"),
            'id',
            'number',
            'type'

        )
            ->orderBy('number', 'desc')
            ->get();
        return $vehicles;
    }

    //list all the vehicles
    public function vehicles()
    {

        //get vehicles
        $vehicles = Vehicle::
        select(
            DB::raw("CONCAT('type', ' ', 'number')   as name"),
            'id',
            'number',
            'type'

        )
            ->orderBy('number', 'desc')
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
            $vehicle['marksByCategory'] = $marksByCategory;

        }


        return $vehicles;
    }


    //authenticate vehicle
    public function authenticateVehicle(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'password' => 'required|string',
        ]);
        //get vehicles
        $vehicle = Vehicle::
        select(
            DB::raw("CONCAT(type, ' ', number)   as name"),
            'id',
            'number',
            'type',
            'password'

        )
            ->whereId($validatedData['id'])
            ->first();
        if ($vehicle) {
            if ($vehicle->password == $validatedData['password']) {

                return Response::json(array(
                    'code' => 200,
                    'message' => "authenticated ",
                    'data' => $vehicle,
                ), 200);
            } else {

                return Response::json(array(
                    'code' => 400,
                    'message' => "password incorrect "
                ), 300);
            }
        }
        return Response::json(array(
            'code' => 400,
            'message' => "Vehicle Id incorrect "
        ), 400);
    }

}
