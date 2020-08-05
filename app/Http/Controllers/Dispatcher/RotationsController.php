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
    //this function called after any changement in rotaions to update total marks for a vehicle
    public function recalculatedTotalMarks($vehicleId)
    {
        $rotationsCategories = RotationsCategory::get();
        foreach ($rotationsCategories as $rotationsCategory) {
            $currentCategoryId = $rotationsCategory->id;
            $currentCategoryName = $rotationsCategory->name;
            $sumOfMarksOfCurrentCategory =
                Rotation::
                where("rotation_category_id", $currentCategoryId)
                    ->where("vehicle_id", $vehicleId)
                    ->sum('marks');
            $markRowToUpdate =
                Marks::
                where('rotation_category_id', $currentCategoryId)
                    ->where('vehicle_id', $vehicleId)
                    ->first();
            if ($markRowToUpdate) {
                $markRowToUpdate->marks = $sumOfMarksOfCurrentCategory;
                $markRowToUpdate->save();
            } else {
                $markRowToUpdate = new Marks;
                $markRowToUpdate->vehicle_id = $vehicleId;
                $markRowToUpdate->rotation_category_id = $currentCategoryId;
                $markRowToUpdate->rotation_category_name = $currentCategoryName;
                $markRowToUpdate->marks = $sumOfMarksOfCurrentCategory;
                $markRowToUpdate->save();
            }

        }

    }

    //recalculate all vehicles marks
    public function recalculateAllVehiclesMarks()
    {
        $vehicles = Vehicle::get();
        foreach ($vehicles as $vehicle) {
            echo "-------------------------vehicle id : " . $vehicle->id . "---------------------------------" . "<br>";
            $rotationsCategories = RotationsCategory::get();
            foreach ($rotationsCategories as $rotationsCategory) {
                $marks = Marks::
                select('marks')
                    ->where('rotation_category_id', $rotationsCategory->id)
                    ->where('vehicle_id', $vehicle->id)
                    ->first();
                echo "old value of " . $rotationsCategory->name . " has : " . $marks . "<br>";

            }
            //update marks
            $this->recalculatedTotalMarks($vehicle->id);
            echo "------------------" . "<br>";
            foreach ($rotationsCategories as $rotationsCategory) {
                $marks = Marks::
                select('marks')
                    ->where('rotation_category_id', $rotationsCategory->id)
                    ->where('vehicle_id', $vehicle->id)
                    ->first();
                echo "new value of " . $rotationsCategory->name . " has : " . $marks . "<br>";

            }
        }
    }

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
            'searchByDate' => 'sometimes',
            'searchByCategoryId' => 'sometimes'
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
            ) {
                if (array_key_exists("searchByVehicleNumber", $data)) {
                    $q->where('vehicles.number', 'LIKE', $data['searchByVehicleNumber']);
                }
                if (array_key_exists("searchByBadgeId", $data)) {

                    $q->where('badge_id', 'LIKE', $data['searchByBadgeId']);
                }
                if (array_key_exists("searchByJobId", $data)) {

                    $q->where('job_id', 'LIKE', $data['searchByJobId']);
                }
                if (array_key_exists("searchByCategoryId", $data)) {

                    $q->where("rotation_category_id", $data['searchByCategoryId']);
                }
                if (array_key_exists("searchByDate", $data)) {
                    $date = strtotime($data['searchByDate']);
                    $date = date('Y-m-d', $date);
                    $q->where('date', 'LIKE', '%' . $date . '%');
                }
            })
            ->orderBy('date', 'desc')
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
            $rotation->marks = $data['marks'];
            $rotation->save();
            $this->recalculatedTotalMarks($rotation->vehicle_id);

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
        //update marks
        $this->recalculatedTotalMarks($rotation->vehicle_id);
        return new RotationResource($rotation);
    }

    //delete an existing Rotation
    public function destroy($id)
    {
        //* delete rotation *//
        $rotation = Rotation::findOrFail($id);
        if ($rotation->delete()) {
            //update marks
            $this->recalculatedTotalMarks($rotation->vehicle_id);
            return new RotationResource($rotation);
        }

    }


}
