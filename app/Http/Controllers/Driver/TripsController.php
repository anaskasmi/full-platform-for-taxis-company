<?php

namespace App\Http\Controllers\Driver;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trip;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Trip as TripResource;

class TripsController extends Controller
{




    public function index()
    {
        $user = Auth::user();
        $id= $user->PermitNumber;
        $trips= Trip::
        join('drivers','PermitNumber','=','badge_id')
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
        ->where('drivers.PermitNumber','=',$id )
        ->orderByRaw('date DESC')
        ->orderByRaw('trip_id')
        ->paginate(20)
        ->appends(request()->query());
        return TripResource::collection($trips);
    }



    


    public function search(Request $request)
    {
        $user = Auth::user();
        $id= $user->PermitNumber;
        //Search by Job id
        if($request->input('searchbyjobid'))
        {
            if (! is_numeric($request->input('searchbyjobid')))
            {
                return response()->json([
                    'error' => "Trip Id must be numeric",
                ]);
            }
            $data = request()->validate([
                'searchbyjobid' => 'numeric',
            ]);

            $trips= Trip::
            join('drivers','PermitNumber','=','badge_id')
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
            ->where('job_id','=',$data['searchbyjobid'])
            ->where('drivers.PermitNumber','=',$id )
            ->orderByRaw('trip_id DESC')
            ->paginate(20)
            ->appends(request()->query());
                
            return TripResource::collection($trips);

        }
        //Search by Date
        if($request->input('searchbydate'))
        {
            $data = request()->validate([
                'searchbydate' => 'date',
                        ]);
            $date = strtotime($data['searchbydate']);
            $date = date('Y-m-d',$date);   
            
            $trips= Trip::
            join('drivers','PermitNumber','=','badge_id')
            ->where('date','LIKE','%'.$date.'%')
            ->where('drivers.PermitNumber','=',$id )
            ->orderByRaw('date DESC')
            ->orderByRaw('test_id')
            ->paginate(20)
            ->appends(request()->query());
            return TripResource::collection($trips);

        }
            //Search by customer
        if($request->input('searchbycustomer'))
        {
            $data = request()->validate([
                'searchbycustomer' => 'required',
                        ]);
            $trips= Trip::join('drivers','PermitNumber','=','badge_id')
            ->where('customer_name','LIKE','%'.$data['searchbycustomer'].'%')
            ->where('drivers.PermitNumber','=',$id )
            ->orderByRaw('date DESC')
            ->paginate(20)
            ->appends(request()->query());
            return TripResource::collection($trips);

        }
    }



    public function show($trip_id)
    {
        $user = Auth::user();
        $id= $user->PermitNumber;
        $trips= Trip::join('drivers','PermitNumber','=','badge_id')
        ->where('trip_id','=',$trip_id)
        ->where('drivers.PermitNumber','=',$id )
        ->get();
        $trip = $trips[0];
        return new TripResource($trip);
    }





    
    public function store(Request $request)
    {
        $user = Auth::user();
        $id= $user->PermitNumber;
        $data = request()->validate([
           'job_id'=>'numeric|Nullable',
           'date'=>'Date|required',
           'vehicle'=>'numeric',
           'customer_name'=>'required',
           'fare'=>'numeric|required',
           'tip'=>'required|numeric',
           'account'=>'required',
           'po'=>'required',
           'pickup'=>'required',
           'dropoff'=>'required',
           'pickup_date'=>'Date',
           'dropoff__date'=>'Date',

                    ]);
        $data['total']=$data['tip']+$data['fare'];
        $data['badge_id']=$id;
        $trip = Trip::create($data);
        return new TripResource($trip);
    }




    

    public function update(Request $request, $trip_id)
    {
        $user = Auth::user();
        $id= $user->PermitNumber;

            $data = request()->validate([
                'job_id'=>'numeric|Nullable',
                'date'=>'Date|required',
                'vehicle'=>'numeric',
                'customer_name'=>'required',
                'fare'=>'numeric|required',
                'tip'=>'required|numeric',
                'account'=>'required',
                'po'=>'required',
                'pickup'=>'required',
                'dropoff'=>'required',
                'pickup_date'=>'Date',
                'dropoff__date'=>'Date',
            ]);

            $data['total']=$data['tip']+$data['fare'];
            $data['badge_id']=$id;

            Trip::
            where('trip_id', $trip_id)
            ->where('badge_id','=',$id )
            ->update($data);
            $trip =  Trip::where('trip_id', $trip_id)
            ->first();
            return new TripResource($trip);

    }



    public function destroy($trip_id)
    {
        $user = Auth::user();
        $id= $user->PermitNumber;

        $trip =  Trip::
        where('trip_id', $trip_id)
        ->where('badge_id','=',$id )
        ->first();
        if($trip->delete())
        {
            return new TripResource($trip);
        }

    }
    
}
