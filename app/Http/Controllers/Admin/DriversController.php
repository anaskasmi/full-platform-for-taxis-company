<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Driver;
use App\Archive;
use App\Http\Resources\Driver as DriverResource;
// use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DriversController extends Controller
{


    public function search(Request $request)
    {
        $data = request()->validate([
            'searchValue' => 'required',
        ]);

        $drivers = Driver::
        where('LastName','LIKE','%'.$data['searchValue'].'%')
            ->orwhere('FirstName','LIKE','%'.$data['searchValue'].'%')
            ->orwhere('PermitNumber','LIKE','%'.$data['searchValue'].'%')
            ->paginate(20)
            ->appends(request()->query());
        return DriverResource::collection($drivers);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = Driver::orderBy('created_at','desc')->paginate(30);
        return DriverResource::collection($drivers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver =  Driver::where('PermitNumber', $id)->first();
        return new DriverResource($driver);
    }



    //store
    public function store(Request $request)
    {

        $data = request()->validate([
            'FirstName' => 'required',
            'LastName' => 'required',
            'LicenseNumber' => 'required',
            'PermitNumber'=>'required|unique:drivers,PermitNumber',
            'HomePhone' => '',
            'TAXI_HOST' => '',
            'ADDRESS' => '',
            'DRIVER_STATUS' => '',
            'LICENSE_CLASS' => '',
            'email' => 'email',
            'LicenseExpiry' => 'date',
            'AbstractExpiry' => 'date',
            'START_DATE' => 'date',
            'END_DATE' => 'date',
            'PermitExpiry'=>'date',
        ]);

        $data['name'] = strtoupper($data['FirstName'][0]).$data['LastName'];
        $this->validateImages();

        if($request->input('password'))
        {
            request()->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $data['password'] = Hash::make($request->input('password'));
            // $data['password'] = $request->input('password');
        }
        else
        {
            $data['password'] = Hash::make('taxi-office');
            // $data['password'] = 'taxi-office';
        }


        $driver = Driver::create($data);
        $this->storeImage($driver);
        return new DriverResource($driver);
    }





    public function update(Request $request, $id)
    {
        $data = request()->validate([
            'FirstName' => 'required',
            'LastName' => 'required',
            'LicenseNumber' => 'required',
            'PermitNumber'=>'required',
            'HomePhone' => '',
            'TAXI_HOST' => '',
            'ADDRESS' => '',
            'DRIVER_STATUS' => '',
            'LICENSE_CLASS' => '',
            'email' => 'email',
            'LicenseExpiry' => 'date',
            'AbstractExpiry' => 'date',
            'START_DATE' => 'date',
            'END_DATE' => 'date',
            'PermitExpiry'=>'date',

        ]);
        //make sure permit number doesnt existe in other rows
        $data['name'] = strtoupper($data['FirstName'][0]).$data['LastName'];

        if($request->input('password'))
        {
            request()->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $data['password'] = Hash::make($request->input('password'));
        }
        try {
            Driver::where('PermitNumber', $id)->update($data);
            $driver=Driver::where('PermitNumber', $id)->first();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => $e->errorInfo[2],
            ]);
        }

        $this->archiveimages($request,$driver->PermitNumber);

        $this->storeImage($driver);


        return new DriverResource($driver);

    }





    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $driver =Driver::where('PermitNumber', $id)->first();

        if($driver->delete())
        {
            return new DriverResource($driver);
        }
    }




    //healpers
    public function archiveimages(Request $request,$badge_id)
    {
        //check if the request has any file and create new intance of Archive
        if(
            request()->hasfile('LICENSE_PATH') ||
            request()->hasfile('PERMIT_PATH') ||
            request()->hasfile('TAXIHOST_PATH') ||
            request()->hasfile('ABSTRACT_PATH') ||
            request()->hasfile('SP_FILE_PATH')
        )
        {
            $archive = new Archive;
            $row = DB::table('drivers')
                ->where ('PermitNumber','=',$badge_id)
                ->get()
                ->toArray();
            $row = $row[0];

        }

        //LICENSE_PATH

        if(
        request()->hasfile('LICENSE_PATH')
        )
        {
            $archive->LICENSE_PATH = $row->LICENSE_PATH;
        }

        // PERMIT_PATH
        if(
        request()->hasfile('PERMIT_PATH')
        )
        {
            $archive->PERMIT_PATH = $row->PERMIT_PATH;
        }

        // TAXIHOST_PATH
        if(
        request()->hasfile('TAXIHOST_PATH')
        )
        {
            $archive->TAXIHOST_PATH = $row->TAXIHOST_PATH;
        }

        // ABSTRACT_PATH
        if(
        request()->hasfile('ABSTRACT_PATH')
        )
        {
            $archive->ABSTRACT_PATH = $row->ABSTRACT_PATH;
        }

        // SP_FILE_PATH
        if(
        request()->hasfile('SP_FILE_PATH')
        )
        {
            $archive->SP_FILE_PATH = $row->SP_FILE_PATH;
        }


        //check if the request has any file and save the intance of Archive after adding badge id
        if(
            request()->hasfile('LICENSE_PATH') ||
            request()->hasfile('PERMIT_PATH') ||
            request()->hasfile('TAXIHOST_PATH') ||
            request()->hasfile('ABSTRACT_PATH') ||
            request()->hasfile('SP_FILE_PATH')
        )
        {
            $archive->badge_id = $badge_id;
            $archive->save();
        }
    }




    public function validateImages()
    {
        if(request()->hasfile('image'))
        {
            request()->validate(
                [
                    'image'=>'file|image|max:30000',
                ]
            );
        }

        if(request()->hasfile('LICENSE_PATH'))
        {
            request()->validate(
                [
                    'LICENSE_PATH'=>'file|mimes:jpeg,jpg,gif,bmp,png,pdf|max:40000',
                ]
            );
        }

        if(request()->hasfile('SP_FILE_PATH'))
        {
            request()->validate(
                [
                    'SP_FILE_PATH'=>'file|mimes:jpeg,jpg,gif,bmp,png,pdf|max:40000',
                ]
            );
        }


        if(request()->hasfile('PERMIT_PATH'))
        {
            request()->validate(
                [
                    'PERMIT_PATH'=>'file|mimes:jpeg,jpg,gif,bmp,png,pdf|max:40000',
                ]
            );
        }


        if(request()->hasfile('TAXIHOST_PATH'))
        {
            request()->validate(
                [
                    'TAXIHOST_PATH'=>'file|mimes:jpeg,jpg,gif,bmp,png,pdf|max:40000',
                ]
            );
        }


        if(request()->hasfile('ABSTRACT_PATH'))
        {
            request()->validate(
                [
                    'ABSTRACT_PATH'=>'file|mimes:jpeg,jpg,gif,bmp,png,pdf|max:40000',
                ]
            );
        }
    }







    public function storeImage($driver)
    {
        if(request()->hasfile('image'))
        {
            $driver->update([
                'image'=>request()->image->store('uploads/IMAGES','public'),
            ]);
        }

        if(request()->hasfile('LICENSE_PATH'))
        {
            $driver->update([
                'LICENSE_PATH'=>request()->LICENSE_PATH->store('uploads/LICENSE FILES','public'),
            ]);
        }

        if(request()->hasfile('SP_FILE_PATH'))
        {
            $driver->update([
                'SP_FILE_PATH'=>request()->SP_FILE_PATH->store('uploads/SP FILES','public'),
            ]);
        }

        if(request()->hasfile('PERMIT_PATH'))
        {
            $driver->update([
                'PERMIT_PATH'=>request()->PERMIT_PATH->store('uploads/PERMIT FILES','public'),
            ]);
        }

        if(request()->hasfile('TAXIHOST_PATH'))
        {
            $driver->update([
                'TAXIHOST_PATH'=>request()->TAXIHOST_PATH->store('uploads/TAXIHOST FILES','public'),
            ]);
        }

        if(request()->hasfile('ABSTRACT_PATH'))
        {
            $driver->update([
                'ABSTRACT_PATH'=>request()->ABSTRACT_PATH->store('uploads/ABSTRACT FILES','public'),
            ]);
        }
    }


}
