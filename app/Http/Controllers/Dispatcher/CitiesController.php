<?php

namespace App\Http\Controllers\Dispatcher;

use App\City;
use App\Http\Controllers\Controller;
use App\Http\Resources\City as CityResource;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    //list all the Cities
    public function cities()
    {
        $cities = City::
            select(
            'id',
            'name'

        )
            ->orderBy('created_at', 'desc')
            ->get();
        return CityResource::collection($cities);
    }

    //get an City by id
    public function show($id)
    {
        $cities = City::
            select(
            'id',
            'name'

        )
            ->where('id', '=', $id)
            ->firstOrFail();
        return new CityResource($cities);
    }

    //update an existing City
    public function update(Request $request, $id)
    {

        $data = request()->validate([
            'name' => 'required',

        ]);

        try {
            City::
                whereId($id)
                ->update($data);
            $city = City::whereId($id)->get();
        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
        return new CityResource($city);

    }

    //store
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',

        ]);

        $city = City::create($data);
        return new CityResource($city);
    }

    //delete an existing City
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        if ($city->delete()) {
            return new CityResource($city);
        }
    }

}
