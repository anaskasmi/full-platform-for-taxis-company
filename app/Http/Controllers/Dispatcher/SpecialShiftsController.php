<?php

namespace App\Http\Controllers\Dispatcher;
use App\SpecialShift;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SpecialShift as SpecialShiftResource;
use Auth;
class SpecialShiftsController extends Controller
{

    
  //search specialShifts
  public function search(Request $request)
  {
      $data = request()->validate([
          'searchValue' => 'required',
                  ]);
      
        $specialShifts  =  SpecialShift::
        join('drivers','drivers.PermitNumber','=','specialShifts.PermitNumber')
        ->leftJoin('dispatchers','dispatchers.id','=','specialShifts.dispatcher_id')
        ->select(
          'specialShifts.id',
          'specialShifts.PermitNumber',
          'specialShifts.ShiftType',
          'specialShifts.Car',
          'specialShifts.CarCoveredBy',
          'specialShifts.SingInTime',
          'specialShifts.dispatcher_id',
          DB::raw('dispatchers.name as dispatchersName'),
          'drivers.FirstName',
          'drivers.LastName'
          )
      ->where('LastName','LIKE','%'.$data['searchValue'].'%')
      ->orwhere('FirstName','LIKE','%'.$data['searchValue'].'%')
      ->orwhere('specialShifts.PermitNumber','LIKE','%'.$data['searchValue'].'%')
      ->paginate(20)
      ->appends(request()->query());
        return SpecialShiftResource::collection($specialShifts);

  }



//search mySpecialShifts
public function searchMySpecialShifts(Request $request)
{
    $data = request()->validate([
        'searchValue' => 'required',
                ]);
    
      $specialShifts  =  SpecialShift::
      join('drivers','drivers.PermitNumber','=','specialShifts.PermitNumber')
      ->leftJoin('dispatchers','dispatchers.id','=','specialShifts.dispatcher_id')
      ->select(
        'specialShifts.id',
        'specialShifts.PermitNumber',
        'specialShifts.ShiftType',
        'specialShifts.Car',
        'specialShifts.CarCoveredBy',
        'specialShifts.SingInTime',
        'specialShifts.dispatcher_id',
        DB::raw('dispatchers.name as dispatchersName'),
        'drivers.FirstName',
        'drivers.LastName'
        )
    ->where('LastName','LIKE','%'.$data['searchValue'].'%')
    ->where('specialShifts.dispatcher_id',"=",Auth::user()->id)
    ->orwhere('FirstName','LIKE','%'.$data['searchValue'].'%')
    ->orwhere('specialShifts.PermitNumber','LIKE','%'.$data['searchValue'].'%')
    ->paginate(20)
    ->appends(request()->query());
      return SpecialShiftResource::collection($specialShifts);

}


    //list all the comments
    public function specialShifts()
    {
        $specialShifts  =  SpecialShift::
        join('drivers','drivers.PermitNumber','=','specialShifts.PermitNumber')
        ->leftJoin('dispatchers','dispatchers.id','=','specialShifts.dispatcher_id')
        ->select(
            'specialShifts.id',
            'specialShifts.PermitNumber',
            'specialShifts.ShiftType',
            'specialShifts.Car',
            'specialShifts.CarCoveredBy',
            'specialShifts.SingInTime',
            'specialShifts.dispatcher_id',
            'drivers.FirstName',
            'drivers.LastName',
            DB::raw('dispatchers.name as dispatchersName')
            )
        ->orderBy('specialShifts.created_at','desc')
        ->paginate(20)
        ->appends(request()->query());
        return SpecialShiftResource::collection($specialShifts);
    }



    public function mySpecialShifts()
    {
        $mySpecialShifts = SpecialShift::
        join('drivers','drivers.PermitNumber','=','specialShifts.PermitNumber')
        ->leftJoin('dispatchers','dispatchers.id','=','specialShifts.dispatcher_id')
        ->select(
            'specialShifts.id',
            'specialShifts.PermitNumber',
            'specialShifts.ShiftType',
            'specialShifts.Car',
            'specialShifts.CarCoveredBy',
            'specialShifts.SingInTime',
            'specialShifts.dispatcher_id',
            'drivers.FirstName',
            'drivers.LastName',
            DB::raw('dispatchers.name as dispatchersName')
            )
        ->where('specialShifts.dispatcher_id',"=",Auth::user()->id)
        ->orderBy('specialShifts.created_at','desc')
        ->paginate(20)
        ->appends(request()->query());
        return SpecialShiftResource::collection($mySpecialShifts);
    }

    //show a single comment
    public function show($id)
    {
             $specialShift  =  SpecialShift::
                join('drivers','drivers.PermitNumber','=','specialShifts.PermitNumber')
                ->leftJoin('dispatchers','dispatchers.id','=','specialShifts.dispatcher_id')
                ->select(
                'specialShifts.id',
                'specialShifts.PermitNumber',
                'specialShifts.ShiftType',
                'specialShifts.Car',
                'specialShifts.CarCoveredBy',
                'specialShifts.dispatcher_id',
                'specialShifts.SingInTime',
                'drivers.FirstName',
                'drivers.LastName',
                DB::raw('dispatchers.name as dispatchersName')
                )
                ->where('specialShifts.id','=',$id)
                ->firstOrFail();
            return new SpecialShiftResource($specialShift);
    }
     

    //update an existing comment 
    public function update(Request $request, $id)
    {
        
        $data = request()->validate([
            'PermitNumber' => 'required|exists:drivers,PermitNumber',
            'SingInTime' => '',
            'SingInDate' => '',
            'Car' => 'required',
            'CarCoveredBy'=>'',
            'ShiftType'=>''
                    ]);

        $stamp=strtotime($data['SingInDate'] . ' ' . $data['SingInTime']);
        $data['SingInTime']=date("Y-m-d G:i:s", $stamp);
        unset($data['SingInDate']);
        try {
            SpecialShift::
                whereId($id)
                ->where('specialShifts.dispatcher_id',"=",Auth::user()->id)
                ->update($data);
            $comment=SpecialShift::whereId($id)->get();
        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
        return new SpecialShiftResource($comment);

    }


    //store
    public function store()
    {
            $data = request()->validate([
                'PermitNumber' => 'required|exists:drivers,PermitNumber',
                'SingInTime' => '',
                'SingInDate' => '',
                'Car' => 'required',
                'CarCoveredBy'=>'',
                'ShiftType'=>''
                        ]);
            $stamp=strtotime($data['SingInDate'] . ' ' . $data['SingInTime']);
            $data['SingInTime']=date("Y-m-d G:i:s", $stamp);
            $data['dispatcher_id']=Auth::user()->id;
            unset($data['SingInDate']);
            $comment=SpecialShift::create($data);
            return new SpecialShiftResource($comment);
    }


    //delete an existing comment
    // public function destroy($id)
    public function destroy($id)
    {
        $specialShift = SpecialShift::
            where('specialShifts.dispatcher_id',"=",Auth::user()->id)
            ->findOrFail($id);
        if($specialShift->delete())
        {
            return new SpecialShiftResource($specialShift);
        }
    }



}
