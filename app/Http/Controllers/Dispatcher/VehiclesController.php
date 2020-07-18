<?php

namespace App\Http\Controllers\Dispatcher;

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

    //get an Vehicle by id
    public function show($id)
    {
        $vehicle = Vehicle::
            select(
            'id',
            'number',
            'type'
        )
            ->where('id', '=', $id)
            ->firstOrFail();
        foreach ($rotationsCategories as $rotationsCategory) {
            //check if there is a mark row
            $markRowExiste = Marks::where('vehicle_id', $vehicle->id)
                ->where('rotation_category_id', $rotationsCategory->id)
                ->first();
            if ($markRowExiste) {
                $vehicle->marksByCategory[$rotationsCategory->id] = $markRowExiste->marks;
            }
        }
        return new VehicleResource($vehicle);
    }

    //update an existing Vehicle
    public function update(Request $request, $id)
    {
        
        $data = request()->validate([
            'number' => 'required|numeric',
            'type' => 'required',
        ]);
        //update type and number
        try {
            Vehicle::
                whereId($id)
                ->update($data);
            $vehicle = Vehicle::whereId($id)->first();
        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
        //update marks
        //get all rotation types
        $rotationsCategories = RotationsCategory::
            select(
            'id',
            'name'
        )
            ->get();

        //loop through rotation types(categories)
        foreach ($rotationsCategories as $rotationsCategory) {
            //check if there is a mark row
            $markRowExiste = Marks::where('vehicle_id', $vehicle->id)
                ->where('rotation_category_id', $rotationsCategory->id)
                ->first();
            //if not create one
           

            if (!$markRowExiste) {
                $marksRow = new Marks;
                $marksRow->vehicle_id = $vehicle->id;
                $marksRow->rotation_category_id = $rotationsCategory->id;
                $marksRow->rotation_category_name = $rotationsCategory->name;
                foreach ($request->input('marksByCategory') as $inputCategoryId => $inputTotalMarks) {
                    if ($inputCategoryId == $rotationsCategory->id) {
                        $marksRow->marks = $inputTotalMarks;
                    }
                }
                $marksRow->save();
            }
            //Marks row exist
            else {
                foreach ($request->input('marksByCategory') as $inputCategoryId => $inputTotalMarks) {
                    if ($inputCategoryId == $rotationsCategory->id) {
                        $markRowExiste->marks = $inputTotalMarks;
                    }
                }
                $markRowExiste->save();

            }
        }

        return new VehicleResource($vehicle);

    }

    //store
    public function store(Request $request)
    {
        $data = request()->validate([
            'number' => 'required|numeric',
            'type' => 'required',

        ]);

        //create vehicle
        $vehicle = Vehicle::create($data);
        //get all rotation types
        $rotationsCategories = RotationsCategory::
            select(
            'id',
            'name'
        )
            ->get();

        //loop through rotation types(categories)
        foreach ($rotationsCategories as $rotationsCategory) {
            //check if there is a mark row
            $markRowExiste = Marks::where('vehicle_id', $vehicle->id)
                ->where('rotation_category_id', $rotationsCategory->id)
                ->first();
            //if not create one
            if (!$markRowExiste) {
                $marksRow = new Marks;
                $marksRow->vehicle_id = $vehicle->id;
                $marksRow->rotation_category_id = $rotationsCategory->id;
                $marksRow->rotation_category_name = $rotationsCategory->name;
                foreach ($request->input('marksByCategory') as $inputCategoryId => $inputTotalMarks) {
                    if ($inputCategoryId == $rotationsCategory->id) {
                        $marksRow->marks = $inputTotalMarks;
                    }
                }
                $marksRow->save();
            }
        }
        return new VehicleResource($vehicle);
    }

    //delete an existing Vehicle
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        if ($vehicle->delete()) {
            //get all mark rows whith the same vehicle id
            $markRowsToDelete = Marks::where('vehicle_id', $vehicle->id)
                ->get();
            //loop through all marks rows
            foreach ($markRowsToDelete as $marksRow) {
                //update
                $marksRow->delete();

            }
            return new VehicleResource($vehicle);
        }
    }

}
