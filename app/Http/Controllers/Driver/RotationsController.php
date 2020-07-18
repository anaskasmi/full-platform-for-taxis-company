<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Driver;
use App\City;
use App\Marks;
use App\Trip;
use App\Vehicle;
use App\Rotation;
use App\RotationsCategory;
use App\Http\Resources\Rotation as RotationResource;

class RotationsController extends Controller
{
    //list all the rotations
    public function rotations()
    {
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
    //to get rotation for only one specific rotation category
    public function rotationsByCategory($id)
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
            ->where("rotation_category_id", $id)
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
    public function rotationsByVehicleAndCategory($category_id,$vehicle_id)
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





}
