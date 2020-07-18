<?php

namespace App\Http\Controllers\Dispatcher;

use App\Http\Controllers\Controller;
use App\Http\Resources\RotationsCategory as RotationsCategoryResource;
use App\Marks;
use App\RotationsCategory;
use App\Vehicle;
use Illuminate\Http\Request;

class RotationsCategoriesController extends Controller
{
    //list all the RotationsCategories
    public function RotationsCategories()
    {
        $rotationsCategories = RotationsCategory::
            select(
            'id',
            'name'
        )
            ->orderBy('created_at', 'desc')
            ->get();
        return RotationsCategoryResource::collection($rotationsCategories);
    }

    //get an RotationsCategory by id
    public function show($id)
    {
        $rotationsCategory = RotationsCategory::
            select(
            'id',
            'name'
        )
            ->where('id', '=', $id)
            ->firstOrFail();
        return new RotationsCategoryResource($rotationsCategory);
    }

    //update an existing RotationsCategory
    public function update(Request $request, $id)
    {

        $data = request()->validate([
            'name' => 'required',
        ]);

        try {
            //Update
            RotationsCategory::
                whereId($id)
                ->update($data);
            //get the rotation
            $rotationsCategory = RotationsCategory::whereId($id)->first();
            //get all mark rows whith the same category id
            $markRowsToUpdate = Marks::where('rotation_category_id', $rotationsCategory->id)
                ->get();
            //loop through all marks rows 
            foreach ($markRowsToUpdate as $marksRow) {
                //change the category name
                $marksRow->rotation_category_name = $rotationsCategory->name;
                //update
                $marksRow->save();
               
            }

        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
        return new RotationsCategoryResource($rotationsCategory);

    }

    //store
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
        ]);

        $rotationsCategory = RotationsCategory::create($data);

        //create marks row for each car with the rotation_category_id = this rotation type id
        //get all vehicles
        $vehicles = Vehicle::
            select(
            'id',
            'number',
            'type'

        )
            ->orderBy('created_at', 'desc')
            ->get();
        //loop through all vehicles
        foreach ($vehicles as $vehicle) {
            //check if there is a mark row
            $markRowToUpdate = Marks::where('vehicle_id', $vehicle->id)
                ->where('rotation_category_id', $rotationsCategory->id)
                ->first();
            //if not create one
            if (!$markRowToUpdate) {
                $marksRow = new Marks;
                $marksRow->vehicle_id = $vehicle->id;
                $marksRow->rotation_category_id = $rotationsCategory->id;
                $marksRow->rotation_category_name = $rotationsCategory->name;
                $marksRow->marks = 0;
                $marksRow->save();
            }
        }
        return new RotationsCategoryResource($rotationsCategory);
    }

    //delete an existing RotationsCategory
    public function destroy($id)
    {
        $rotationsCategory = RotationsCategory::findOrFail($id);
        if ($rotationsCategory->delete()) {
            return new RotationsCategoryResource($rotationsCategory);
        }
    }

}
