<?php

namespace App\Http\Controllers\Dispatcher;

use App\City;
use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Resources\Rotation as RotationResource;
use App\Marks;
use App\Rotation;
use App\RotationsCategory;
use App\Trip;
use App\Vehicle;
use Illuminate\Http\Request;
use Response;
use DB;

class RotationsController extends Controller
{
    //search rotations
    public function search(Request $request)
    {
        //If Search by Job ID
        if ($request->input('searchByJobId')) {
            if (!is_numeric($request->input('searchByJobId'))) {
                return Response::json(array(
                    'code' => 400,
                    'message' => "Account Number must be numeric"
                ), 400);
            }
            $data = request()->validate([
                'searchByJobId' => '',
            ]);
            $rotations = DB::table('rotations')
                ->join('vehicles', 'rotations.vehicle_id', '=', 'vehicles.id')
                ->join('cities', 'rotations.city_id', '=', 'cities.id')
                ->join('rotations_categories', 'rotations.rotation_category_id', '=', 'rotations_categories.id')
                ->join('drivers', 'badge_id', '=', 'drivers.PermitNumber')
                ->select(DB::raw(
                //vehicles
                    'CONCAT(vehicles.type, " ", vehicles.number) AS vehicle'),
                    //rotation
                    'rotations.id as id',
                    'rotations.type as type',
                    'rotations.marks as marks',
                    'rotations.job_id as job_id',
                    'rotations.date as date',
                    'rotations.note as note',
                    //city
                    'cities.id as city_id',
                    'cities.name as city',
                    //category
                    'rotations_categories.name as rotationsCategory',
                    //driver
                    'drivers.PermitNumber as badge_id',
                    'drivers.Name as driver',
                    'drivers.image as driverImageUrl'
                )
                ->where('job_id', 'LIKE', '%' . $data['searchByJobId'] . '%')
                ->orderBy('marks', 'desc')
                ->paginate(20)
                ->appends(request()->query());
            return $rotations;
        }
        //Search by searchByDate
        if ($request->input('searchByDate')) {
            $data = request()->validate([
                'searchByDate' => 'date',
            ]);
            $date = strtotime($data['searchByDate']);
            $date = date('Y-m-d', $date);

            $rotations = DB::table('rotations')
                ->join('vehicles', 'rotations.vehicle_id', '=', 'vehicles.id')
                ->join('cities', 'rotations.city_id', '=', 'cities.id')
                ->join('rotations_categories', 'rotations.rotation_category_id', '=', 'rotations_categories.id')
                ->join('drivers', 'badge_id', '=', 'drivers.PermitNumber')
                ->select(DB::raw(
                //vehicles
                    'CONCAT(vehicles.type, " ", vehicles.number) AS vehicle'),
                    //rotation
                    'rotations.id as id',
                    'rotations.type as type',
                    'rotations.marks as marks',
                    'rotations.job_id as job_id',
                    'rotations.date as date',
                    'rotations.note as note',
                    //city
                    'cities.id as city_id',
                    'cities.name as city',
                    //category
                    'rotations_categories.name as rotationsCategory',
                    //driver
                    'drivers.PermitNumber as badge_id',
                    'drivers.Name as driver',
                    'drivers.image as driverImageUrl'
                )
                ->where('date', 'LIKE', '%' . $date . '%')
                ->orderBy('marks', 'desc')
                ->paginate(20)
                ->appends(request()->query());
            return $rotations;

        }
        //Search by search By Badge Id
        if ($request->input('searchByBadgeId')) {
            $data = request()->validate([
                'searchByBadgeId' => '',
            ]);

            $rotations = DB::table('rotations')
                ->join('vehicles', 'rotations.vehicle_id', '=', 'vehicles.id')
                ->join('cities', 'rotations.city_id', '=', 'cities.id')
                ->join('rotations_categories', 'rotations.rotation_category_id', '=', 'rotations_categories.id')
                ->join('drivers', 'badge_id', '=', 'drivers.PermitNumber')
                ->select(DB::raw(
                //vehicles
                    'CONCAT(vehicles.type, " ", vehicles.number) AS vehicle'),
                    //rotation
                    'rotations.id as id',
                    'rotations.type as type',
                    'rotations.marks as marks',
                    'rotations.job_id as job_id',
                    'rotations.date as date',
                    'rotations.note as note',
                    //city
                    'cities.id as city_id',
                    'cities.name as city',
                    //category
                    'rotations_categories.name as rotationsCategory',
                    //driver
                    'drivers.PermitNumber as badge_id',
                    'drivers.Name as driver',
                    'drivers.image as driverImageUrl'
                )
                ->where('badge_id', 'LIKE', '%' . $data['searchByBadgeId'] . '%')
                ->orderBy('marks', 'desc')
                ->paginate(20)
                ->appends(request()->query());
            return $rotations;
        }
        //search by vehicle number
        if ($request->input('searchByVehicleNumber')) {
            $data = request()->validate([
                'searchByVehicleNumber' => '',
            ]);

            $rotations = DB::table('rotations')
                ->join('vehicles', 'rotations.vehicle_id', '=', 'vehicles.id')
                ->join('cities', 'rotations.city_id', '=', 'cities.id')
                ->join('rotations_categories', 'rotations.rotation_category_id', '=', 'rotations_categories.id')
                ->join('drivers', 'badge_id', '=', 'drivers.PermitNumber')
                ->select(DB::raw(
                //vehicles
                    'CONCAT(vehicles.type, " ", vehicles.number) AS vehicle'),
                    //rotation
                    'rotations.id as id',
                    'rotations.type as type',
                    'rotations.marks as marks',
                    'rotations.job_id as job_id',
                    'rotations.date as date',
                    'rotations.note as note',
                    //city
                    'cities.id as city_id',
                    'cities.name as city',
                    //category
                    'rotations_categories.name as rotationsCategory',
                    //driver
                    'drivers.PermitNumber as badge_id',
                    'drivers.Name as driver',
                    'drivers.image as driverImageUrl'
                )
                ->where('vehicles.number', 'LIKE', '%' . $data['searchByVehicleNumber'] . '%')
                ->orderBy('marks', 'desc')
                ->paginate(10)
                ->appends(request()->query());
            return $rotations;

        }


    }

    //list all the rotations
    public function rotations(Request $request)
    {

        $data = request()->validate([
            'searchByVehicleNumber' => 'sometimes',
            'searchByBadgeId' => 'sometimes',
            'searchByJobId' => 'sometimes',
            'searchByDate' => 'sometimes|date',
            'categoryId'=>'sometimes|numeric'
        ]);
        $rotations = DB::table('rotations')
            ->join('vehicles', 'rotations.vehicle_id', '=', 'vehicles.id')
            ->join('cities', 'rotations.city_id', '=', 'cities.id')
            ->join('rotations_categories', 'rotations.rotation_category_id', '=', 'rotations_categories.id')
            ->join('drivers', 'badge_id', '=', 'drivers.PermitNumber')
            ->select(DB::raw(
            //vehicles
                'CONCAT(vehicles.type, " ", vehicles.number) AS vehicle'),
                //rotation
                'rotations.id as id',
                'rotations.type as type',
                'rotations.marks as marks',
                'rotations.job_id as job_id',
                'rotations.date as date',
                'rotations.note as note',
                //city
                'cities.id as city_id',
                'cities.name as city',
                //category
                'rotations_categories.name as rotationsCategory',
                'rotations_categories.id as rotation_category_id',
                //driver
                'drivers.PermitNumber as badge_id',
                'drivers.Name as driver',
                'drivers.image as driverImageUrl'
            )

            ->where(function ($q) use (
                $data
//                $data["searchByVehicleNumber"],$data["searchByBadgeId"],$data["searchByJobId"],$data["searchByDate"]
            ) {
                if (array_key_exists("searchByVehicleNumber", $data)) {
                    $q->where('vehicles.number', 'LIKE', '%' . $data['searchByVehicleNumber'] . '%');
                }
                if (array_key_exists("searchByBadgeId", $data)) {

                    $q->where('badge_id', 'LIKE', '%' . $data['searchByBadgeId'] . '%');
                }
                if (array_key_exists("searchByJobId", $data)) {

                    $q->where('job_id', 'LIKE', '%' . $data['searchByJobId'] . '%');
                }
                if (array_key_exists("categoryId", $data)) {

                    $q->where("rotation_category_id", $data['categoryId']);
                }
                if (array_key_exists("searchByDate", $data)) {
                    $date = strtotime($data['searchByDate']);
                    $date = date('Y-m-d', $date);
                    $q->where('date', 'LIKE', '%' . $date . '%');
                }
            })
            ->orderBy('marks', 'desc')
            ->paginate(20)
            ->appends(request()->query());
        return $rotations;

    }

    //to get rotation for only one specific rotation category
    public function rotationsByCategory($id, Request $request)
    {

        $data = request()->validate([
            'searchByVehicleNumber' => 'sometimes',
            'searchByBadgeId' => 'sometimes',
            'searchByJobId' => 'sometimes',
            'searchByDate' => 'sometimes|date',
        ]);
        $rotations = DB::table('rotations')
            ->join('vehicles', 'rotations.vehicle_id', '=', 'vehicles.id')
            ->join('cities', 'rotations.city_id', '=', 'cities.id')
            ->join('rotations_categories', 'rotations.rotation_category_id', '=', 'rotations_categories.id')
            ->join('drivers', 'badge_id', '=', 'drivers.PermitNumber')
            ->select(DB::raw(
            //vehicles
                'CONCAT(vehicles.type, " ", vehicles.number) AS vehicle'),
                //rotation
                'rotations.id as id',
                'rotations.type as type',
                'rotations.marks as marks',
                'rotations.job_id as job_id',
                'rotations.date as date',
                'rotations.note as note',
                //city
                'cities.id as city_id',
                'cities.name as city',
                //category
                'rotations_categories.name as rotationsCategory',
                //driver
                'drivers.PermitNumber as badge_id',
                'drivers.Name as driver',
                'drivers.image as driverImageUrl'
            )
            ->where("rotation_category_id", $id)
            ->where(function ($q) use (
                $data
//                $data["searchByVehicleNumber"],$data["searchByBadgeId"],$data["searchByJobId"],$data["searchByDate"]
            ) {


                if (array_key_exists("searchByVehicleNumber", $data)) {

                    $q->where('vehicles.number', 'LIKE', '%' . $data['searchByVehicleNumber'] . '%');
                }
                if (array_key_exists("searchByBadgeId", $data)) {

                    $q->where('badge_id', 'LIKE', '%' . $data['searchByBadgeId'] . '%');
                }
                if (array_key_exists("searchByJobId", $data)) {

                    $q->where('job_id', 'LIKE', '%' . $data['searchByJobId'] . '%');
                }
                if (array_key_exists("searchByDate", $data)) {
                    $date = strtotime($data['searchByDate']);
                    $date = date('Y-m-d', $date);
                    $q->where('date', 'LIKE', '%' . $date . '%');
                }
            })
            ->orderBy('marks', 'desc')
            ->paginate(20)
            ->appends(request()->query());
        return $rotations;

    }

    //to get rotation for only one specific Vehicle
    public function rotationsByVehicle($id)
    {
        $arrayData = [];
        $rotations = Rotation::
        select(
            'id',
            'vehicle_id',
            'city_id',
            'type',
            'marks',
            'job_id',
            'badge_id',
            'date',
            'rotation_category_id',
            'note'
        )
            ->where("vehicle_id", $id)
            ->orderBy('created_at', 'desc')
            ->get();
        // return RotationResource::collection($rotations);
        foreach ($rotations as $rotation) {
            $data['badge_id'] = $rotation->badge_id;

            //get job_id
            $trip = Trip::
            join('drivers', 'PermitNumber', '=', 'badge_id')
                ->select(
                    'trip_id',
                    'job_id',
                    'date',
                    'vehicle',
                    'customer_name',
                    'fare',
                    'tip',
                    'total',
                    'account',
                    'po',
                    'pickup_date',
                    'dropoff__date',
                    'pickup',
                    'dropoff',
                    'drivers.Name',
                    'drivers.FirstName',
                    'drivers.LastName',
                    'drivers.PermitNumber'

                )
                ->where('job_id', '=', $rotation->job_id)
                ->first();

            //get driver by badge_id
            $driver = Driver::where('PermitNumber', $rotation->badge_id)->first();
            //get city by city_id
            $city = City::
            select(
                'id',
                'name'

            )
                ->where('id', '=', $rotation->city_id)
                ->first();

            //get vehicle_id
            $vehicle = Vehicle::
            select(
                'id',
                'number',
                'type'
            )
                ->where('id', '=', $rotation->vehicle_id)
                ->first();
            //get rotation_category_id
            $rotationsCategory = RotationsCategory::
            select(
                'id',
                'name'
            )
                ->where('id', '=', $rotation->rotation_category_id)
                ->first();
            //affecting data
            if ($rotation) {
                $data['id'] = $rotation->id;
                $data['type'] = $rotation->type;
                $data['job_id'] = $rotation->job_id;
                $data['marks'] = $rotation->marks;
                $data['date'] = $rotation->date;
                $data['note'] = $rotation->note;
            }

            if ($trip) {
                $data['trip_id'] = $trip->trip_id;
                $data['customer_name'] = $trip->customer_name;
                $data['total'] = $trip->total;
                $data['pickup_date'] = $trip->pickup_date;
                $data['dropoff__date'] = $trip->dropoff__date;
                $data['pickup'] = $trip->pickup;
                $data['dropoff'] = $trip->dropoff;
            }
            if ($driver) {
                $data['driver'] = $driver->Name;
                $data['driverImageUrl'] = $driver->image;
            }
            if ($vehicle) {

                $data['vehicle'] = $vehicle->type . " " . $vehicle->number;
                $data['vehicle_id'] = $vehicle->id;
            }
            if ($rotationsCategory) {
                $data['rotationsCategory'] = $rotationsCategory->name;
                $data['rotation_category_id'] = $rotationsCategory->id;
            }
            if ($city) {
                $data['city'] = $city->name;
                $data['city_id'] = $city->id;
            }
            $arrayData[] = $data;
        }
        return $arrayData;

    }


    //to get rotation for only one specific rotation category and specific car
    public function rotationsByVehicleAndCategory($category_id, $vehicle_id)
    {
        $arrayData = [];
        $rotations = Rotation::
        select(
            'id',
            'vehicle_id',
            'city_id',
            'type',
            'marks',
            'job_id',
            'badge_id',
            'date',
            'rotation_category_id',
            'note'
        )
            ->where("rotation_category_id", $category_id)
            ->where("vehicle_id", $vehicle_id)
            ->orderBy('created_at', 'desc')
            ->get();
        // return RotationResource::collection($rotations);
        foreach ($rotations as $rotation) {
            $data['badge_id'] = $rotation->badge_id;

            //get job_id
            $trip = Trip::
            join('drivers', 'PermitNumber', '=', 'badge_id')
                ->select(
                    'trip_id',
                    'job_id',
                    'date',
                    'vehicle',
                    'customer_name',
                    'fare',
                    'tip',
                    'total',
                    'account',
                    'po',
                    'pickup_date',
                    'dropoff__date',
                    'pickup',
                    'dropoff',
                    'drivers.Name',
                    'drivers.FirstName',
                    'drivers.LastName',
                    'drivers.PermitNumber'

                )
                ->where('job_id', '=', $rotation->job_id)
                ->first();

            //get driver by badge_id
            $driver = Driver::where('PermitNumber', $rotation->badge_id)->first();
            //get city by city_id
            $city = City::
            select(
                'id',
                'name'

            )
                ->where('id', '=', $rotation->city_id)
                ->first();

            //get vehicle_id
            $vehicle = Vehicle::
            select(
                'id',
                'number',
                'type'
            )
                ->where('id', '=', $rotation->vehicle_id)
                ->first();
            //get rotation_category_id
            $rotationsCategory = RotationsCategory::
            select(
                'id',
                'name'
            )
                ->where('id', '=', $rotation->rotation_category_id)
                ->first();
            //affecting data
            if ($rotation) {
                $data['id'] = $rotation->id;
                $data['type'] = $rotation->type;
                $data['job_id'] = $rotation->job_id;
                $data['marks'] = $rotation->marks;
                $data['date'] = $rotation->date;
                $data['note'] = $rotation->note;
            }

            if ($trip) {
                $data['trip_id'] = $trip->trip_id;
                $data['customer_name'] = $trip->customer_name;
                $data['total'] = $trip->total;
                $data['pickup_date'] = $trip->pickup_date;
                $data['dropoff__date'] = $trip->dropoff__date;
                $data['pickup'] = $trip->pickup;
                $data['dropoff'] = $trip->dropoff;
            }
            if ($driver) {
                $data['driver'] = $driver->Name;
                $data['driverImageUrl'] = $driver->image;
            }
            if ($vehicle) {

                $data['vehicle'] = $vehicle->type . " " . $vehicle->number;
                $data['vehicle_id'] = $vehicle->id;
            }
            if ($rotationsCategory) {
                $data['rotationsCategory'] = $rotationsCategory->name;
                $data['rotation_category_id'] = $rotationsCategory->id;
            }
            if ($city) {
                $data['city'] = $city->name;
                $data['city_id'] = $city->id;
            }

            //add data to summary
            // $data['rotation'] = $rotation;
            // $data['trip'] = $trip;
            // $data['driver'] = $driver;
            // $data['vehicle'] = $vehicle;
            // $data['rotationsCategory'] = $rotationsCategory;
            $arrayData[] = $data;
        }
        return $arrayData;

    }

    //get an Rotation by id
    public function show($id)
    {
        $rotation = Rotation::
        select(
            'id',
            'vehicle_id',
            'city_id',
            'type',
            'marks',
            'job_id',
            'badge_id',
            'date',
            'rotation_category_id',
            'note'
        )
            ->where('id', '=', $id)
            ->firstOrFail();
        return new RotationResource($rotation);
    }

    //update an existing Rotation
    public function update(Request $request, $id)
    {

        $data = request()->validate([
            'city_id' => 'required|numeric',
            'type' => 'required',
            'job_id' => 'required|numeric',
            'badge_id' => 'required|numeric',
            'date' => 'required',
            'note' => 'nullable',
            'marks' => 'required|numeric'
        ]);
        $driver = Driver::where('PermitNumber', $data["badge_id"])->first();
        if (!$driver) {
            return Response::json(array(
                'code' => 401,
                'message' => "No Driver Found with Badge Id " . $data["badge_id"]
            ), 400);
        }

        try {


            $rotation = Rotation::whereId($id)->first();

            $rotation->city_id = $data["city_id"];
            $rotation->type = $data["type"];
            $rotation->job_id = $data["job_id"];
            $rotation->badge_id = $data["badge_id"];
            $rotation->date = $data["date"];
            $rotation->date = $data["date"];
            $rotation->note = $data["note"];
            //updating marks on the rotation and on marks table
            if ($rotation->marks != $data['marks']) {
                //check if marks row exist
                $markRowToUpdate =
                    Marks::
                    where('vehicle_id', $rotation->vehicle_id)
                        ->where('rotation_category_id', $rotation->rotation_category_id)
                        ->first();
                //if exist update it
                if ($markRowToUpdate) {
                    //if marks value different than null
                    if ($markRowToUpdate->marks) {
                        //remove the old value and add the new one
                        $markRowToUpdate->marks = $markRowToUpdate->marks - $rotation->marks + $data['marks'];
                    } //if marks value was empty
                    else {
                        $markRowToUpdate->marks = $data['marks'];
                    }
                    //update
                    $markRowToUpdate->save();
                }
                $rotation->marks = $data['marks'];
            }
            $rotation->save();

        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
        return new RotationResource($rotation);

    }


    //store
    public function store()
    {
        $data = request()->validate([
            'vehicle_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'type' => 'required',
            'marks' => 'required|numeric',
            'job_id' => 'required|numeric',
            'badge_id' => 'required|numeric',
            'date' => 'required',
            'rotation_category_id' => 'required|numeric',
            'note' => 'nullable',
        ]);
        $driver = Driver::where('PermitNumber', $data["badge_id"])->first();
        if (!$driver) {
            return Response::json(array(
                'code' => 401,
                'message' => "No Driver Found with Badge Id " . $data["badge_id"]
            ), 400);
        }
        //create rotation
        $rotation = Rotation::create($data);

        //**update marks of the vehicle**//
        //get rotation category because we need the name
        $rotationsCategory = RotationsCategory::whereId($rotation->rotation_category_id)->firstOrFail();
        //check if marks row exist
        $markRowToUpdate =
            Marks::
            where('vehicle_id', $rotation->vehicle_id)
                ->where('rotation_category_id', $rotation->rotation_category_id)
                ->first();
        //if exist update it
        if ($markRowToUpdate) {
            //if marks value different than null
            if ($markRowToUpdate->marks) {
                $markRowToUpdate->marks = $markRowToUpdate->marks + $data['marks'];
            } //if marks value was empty
            else {
                $markRowToUpdate->marks = $data['marks'];
            }
            //update
            $markRowToUpdate->save();
        } //if marks row doesnt exist create new one
        else {
            $marksRow = new Marks;
            $marksRow->vehicle_id = $rotation->vehicle_id;
            $marksRow->rotation_category_id = $rotationsCategory->id;
            $marksRow->rotation_category_name = $rotationsCategory->name;
            $marksRow->marks = $data['marks'];
            $marksRow->save();
        }
        return new RotationResource($rotation);
    }

    //delete an existing Rotation
    public function destroy($id)
    {
        //* delete rotation *//
        $rotation = Rotation::findOrFail($id);
        if ($rotation->delete()) {

            //* downgrad marks for the vehicle **/
            //get rotation category because we need the name
            $rotationsCategory = RotationsCategory::whereId($rotation->rotation_category_id)->firstOrFail();
            //check if marks row exist
            $markRowToUpdate = Marks::where('vehicle_id', $rotation->vehicle_id)
                ->where('rotation_category_id', $rotation->rotation_category_id)
                ->first();
            //if exist update it
            if ($markRowToUpdate) {
                //if marks value different than null
                if ($markRowToUpdate->marks) {
                    $markRowToUpdate->marks = $markRowToUpdate->marks - $rotation->marks;
                } //if marks value was empty
                else {
                    $markRowToUpdate->marks = 0;
                }
                //update
                $markRowToUpdate->save();
            }

            return new RotationResource($rotation);
        }

    }

}
