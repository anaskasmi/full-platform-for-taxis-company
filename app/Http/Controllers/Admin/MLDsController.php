<?php

namespace App\Http\Controllers\Admin;

use App\MLD;
use App\Http\Controllers\Controller;
use App\Http\Resources\MLD as MLDResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MLDsController extends Controller
{

    public function search(Request $request)
    {
        $data = request()->validate([
            'searchValue' => 'required',
        ]);

        $MLDs = MLD::
            where('LastName', 'LIKE', '%' . $data['searchValue'] . '%')
            ->orwhere('FirstName', 'LIKE', '%' . $data['searchValue'] . '%')
            ->orwhere('BadgeID', 'LIKE', '%' . $data['searchValue'] . '%')
            ->paginate(20)
            ->appends(request()->query());
    
        return MLDResource::collection($MLDs);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $MLDs = MLD::orderBy('created_at', 'desc')->paginate(30);
        return MLDResource::collection($MLDs);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $MLD = MLD::where('BadgeID', $id)->first();
        if(!$MLD)
        {
            $response = "No MLD with this Badge ID was found!";
            return response($response, 404);
        }
        return new MLDResource($MLD);
    }

    //store
    public function store(Request $request)
    {
        $data = request()->validate([
            'FirstName' => 'required',
            'LastName' => 'required',
            'MLD_STATUS' => 'required',
            'BadgeID' => 'required|unique:MLDs,BadgeID',
            'email' => 'email',
            'car' => 'required',
        ]);
        
        $data['name'] = strtoupper($data['FirstName'][0]) . $data['LastName'];
        $this->validateImages();
        
        if ($request->input('password')) {
            request()->validate([
                'password' => 'required|string|min:6|confirmed',
                ]);
                $data['password'] = Hash::make($request->input('password'));
        } else {
            $data['password'] = Hash::make('taxi-office');
        }

        $MLD = MLD::create($data);
        $this->storeImage($MLD);
        
        return new MLDResource($MLD);
    }



    public function update(Request $request, $id)
    {
        $MLD = MLD::where('BadgeID', $id)->first();
        if(!$MLD)
        {
            $response = "No MLD with this Badge ID was found!";
            return response($response, 404);
        }
        $data = request()->validate([
            'FirstName' => 'required',
            'LastName' => 'required',
            'MLD_STATUS' => 'required',
            'BadgeID' => 'required',
            'email' => 'email',
            'car' => 'required',
            ]);

        //make sure permit number doesnt existe in other rows
        $data['name'] = strtoupper($data['FirstName'][0]) . $data['LastName'];

        if ($request->input('password')) {
            request()->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $data['password'] = Hash::make($request->input('password'));
        }
        try {
            MLD::where('BadgeID', $id)->update($data);
            $MLD = MLD::where('BadgeID', $id)->first();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => $e->errorInfo[2],
            ]);
        }
        $this->storeImage($MLD);

        return new MLDResource($MLD);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $MLD = MLD::where('BadgeID', $id)->first();

        if ($MLD->delete()) {
            return new MLDResource($MLD);
        }
    }

    public function validateImages()
    {
            if (request()->hasfile('image')) {
                request()->validate(
                    [
                        'image' => 'file|image|max:30000',
                    ]
                );
            }
    }

    public function storeImage($MLD)
    {
        if (request()->hasfile('image')) {
            $MLD->update([
                'image' => request()->image->store('uploads/IMAGES', 'public'),
            ]);
        }
    }

}
