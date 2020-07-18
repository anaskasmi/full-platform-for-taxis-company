<?php

namespace App\Http\Controllers\Driver;

use App\Driver;
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

}
