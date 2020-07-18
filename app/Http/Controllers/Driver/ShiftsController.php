<?php

namespace App\Http\Controllers\Driver;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Resources\Shift as ShiftResource;
use App\Shift;
use App\Trip;
use Illuminate\Support\Facades\Auth;

class ShiftsController extends Controller
{

    // public function index()
    // {
    //     $user = Auth::user();
    //     $id= $user->PermitNumber;
    //     $trips= Trip::
    //     join('drivers','PermitNumber','=','badge_id')
    //     ->select(
    //         'trip_id',
    //         'job_id',
    //         'date',
    //         'vehicle',
    //         'customer_name',
    //         'fare',
    //         'tip',
    //         'total',
    //         'account',
    //         'po',
    //         'pickup_date',
    //         'dropoff__date',
    //         'pickup',
    //         'dropoff',
    //         'drivers.Name',
    //         'drivers.FirstName',
    //         'drivers.LastName',
    //         'drivers.PermitNumber'
    //     )
    //     ->where('drivers.PermitNumber','=',$id )
    //     ->orderByRaw('date DESC')
    //     ->orderByRaw('trip_id')
    //     ->paginate(20)
    //     ->appends(request()->query());
    //     return TripResource::collection($trips);
    // }

    // public function search(Request $request)
    // {
    //     $user = Auth::user();
    //     $id= $user->PermitNumber;
    //     //Search by Job id
    //     if($request->input('searchbyjobid'))
    //     {
    //         if (! is_numeric($request->input('searchbyjobid')))
    //         {
    //             return response()->json([
    //                 'error' => "Trip Id must be numeric",
    //             ]);
    //         }
    //         $data = request()->validate([
    //             'searchbyjobid' => 'numeric',
    //         ]);

    //         $trips= Trip::
    //         join('drivers','PermitNumber','=','badge_id')
    //         ->select(
    //             'trip_id',
    //             'job_id',
    //             'date',
    //             'vehicle',
    //             'customer_name',
    //             'fare',
    //             'tip',
    //             'total',
    //             'account',
    //             'po',
    //             'pickup_date',
    //             'dropoff__date',
    //             'pickup',
    //             'dropoff',
    //             'drivers.Name',
    //             'drivers.FirstName',
    //             'drivers.LastName',
    //             'drivers.PermitNumber'

    //         )
    //         ->where('job_id','=',$data['searchbyjobid'])
    //         ->where('drivers.PermitNumber','=',$id )
    //         ->orderByRaw('trip_id DESC')
    //         ->paginate(20)
    //         ->appends(request()->query());

    //         return TripResource::collection($trips);

    //     }
    //     //Search by Date
    //     if($request->input('searchbydate'))
    //     {
    //         $data = request()->validate([
    //             'searchbydate' => 'date',
    //                     ]);
    //         $date = strtotime($data['searchbydate']);
    //         $date = date('Y-m-d',$date);

    //         $trips= Trip::
    //         join('drivers','PermitNumber','=','badge_id')
    //         ->where('date','LIKE','%'.$date.'%')
    //         ->where('drivers.PermitNumber','=',$id )
    //         ->orderByRaw('date DESC')
    //         ->orderByRaw('test_id')
    //         ->paginate(20)
    //         ->appends(request()->query());
    //         return TripResource::collection($trips);

    //     }
    //         //Search by customer
    //     if($request->input('searchbycustomer'))
    //     {
    //         $data = request()->validate([
    //             'searchbycustomer' => 'required',
    //                     ]);
    //         $trips= Trip::join('drivers','PermitNumber','=','badge_id')
    //         ->where('customer_name','LIKE','%'.$data['searchbycustomer'].'%')
    //         ->where('drivers.PermitNumber','=',$id )
    //         ->orderByRaw('date DESC')
    //         ->paginate(20)
    //         ->appends(request()->query());
    //         return TripResource::collection($trips);

    //     }
    // }
    public function shiftsOfMonth($month, $year)
    {
        $user = Auth::user();
        $id = $user->PermitNumber;
        $shifts = shift::where('driver_badge_id', '=', $id)
            ->whereMonth('shift_start_time', '=', $month)
            ->whereYear('shift_start_time', '=', $year)
            ->orderby('shift_start_time')
            ->get();
        foreach ($shifts as $shift) {
            $shift->break_time = $shift->total_time - $shift->work_time;
        }
        $allShiftsTotal = 0;
        $allShiftsFare = 0;
        $allShiftsTip = 0;
        for ($i = 0; $i < count($shifts); $i++) {
            // get trips of every shift
            // $shifts[$i]= json_decode(json_encode($shifts[$i]), true);

            $result = $this->getTripsOfShift($shifts[$i]['driver_shift_id']);
            // dd($result['trips']);
            //affecr data to every shift
            $shifts[$i]['trips'] = $result['trips'];
            $shifts[$i]['shiftTotal'] = number_format((float) $result['shiftTotal'], 2, '.', '');
            $shifts[$i]['shiftFare'] = number_format((float) $result['shiftFare'], 2, '.', '');
            $shifts[$i]['shiftTip'] = number_format((float) $result['shiftTip'], 2, '.', '');

            //calculate totals
            $allShiftsTotal = $allShiftsTotal + $result['shiftTotal'];
            $allShiftsFare = $allShiftsFare + $result['shiftFare'];
            $allShiftsTip = $allShiftsTip + $result['shiftTip'];

        }
        $totals['allShiftsTotal'] = number_format((float) $allShiftsTotal, 2, '.', '');
        $totals['allShiftsFare'] = number_format((float) $allShiftsFare, 2, '.', '');
        $totals['allShiftsTip'] = number_format((float) $allShiftsTip, 2, '.', '');
        $data['totals'] = $totals;
        $data['shifts'] = $shifts;
        return $data;

    }
    public function show($shift_id)
    {
        $user = Auth::user();
        $id = $user->PermitNumber;
        $shift = shift::join('drivers', 'PermitNumber', '=', 'driver_badge_id')
            ->where('piccolo_shift_id', '=', $shift_id)
            ->where('drivers.PermitNumber', '=', $id)
            ->first();
        $shift->break_time = $shift->total_time - $shift->work_time;
        return new shiftResource($shift);
    }

    public function getTripsOfShift($shift_id)
    {
        //get the authenticated user
        $user = Auth::user();
        $badgeId = $user->PermitNumber;
        //get shift
        $shift = Shift::
            join('drivers', 'drivers.PermitNumber', '=', 'driver_shifts.driver_badge_id')
            ->where('driver_badge_id', '=', $badgeId)
            ->where('driver_shift_id', '=', $shift_id)
            ->first();

        //get times
        $start_time = $shift->shift_start_time;
        $end_time = $shift->shift_end_time;

        //get trips
        $trips = Trip::
            where('date', '>=', $start_time)
            ->where('date', '<=', $end_time)
            ->where('badge_id', '=', $badgeId)
            ->get()
            ->toArray();

        $shiftTotal = 0;
        $shiftFare = 0;
        $shiftTip = 0;
        foreach ($trips as $trip) {
            $shiftTotal = $shiftTotal + $trip['total'];
            $shiftFare = $shiftFare + $trip['fare'];
            $shiftTip = $shiftTip + $trip['tip'];
        }
        $result['trips'] = $trips;
        $result['shiftTotal'] = number_format($shiftTotal, 2, '.', ' ');
        $result['shiftFare'] = number_format($shiftFare, 2, '.', ' ');
        $result['shiftTip'] = number_format($shiftTip, 2, '.', ' ');
        $result['start_time'] = $start_time;
        $result['end_time'] = $end_time;
        return $result;

    }

    // public function store(Request $request)
    // {
    //     $user = Auth::user();
    //     $id= $user->PermitNumber;
    //     $data = request()->validate([
    //        'job_id'=>'numeric|Nullable',
    //        'date'=>'Date|required',
    //        'vehicle'=>'numeric',
    //        'customer_name'=>'required',
    //        'fare'=>'numeric|required',
    //        'tip'=>'required|numeric',
    //        'account'=>'required',
    //        'po'=>'required',
    //        'pickup'=>'required',
    //        'dropoff'=>'required',
    //        'pickup_date'=>'Date',
    //        'dropoff__date'=>'Date',

    //                 ]);
    //     $data['total']=$data['tip']+$data['fare'];
    //     $data['badge_id']=$id;
    //     $trip = Trip::create($data);
    //     return new TripResource($trip);
    // }

    // public function update(Request $request, $trip_id)
    // {
    //     $user = Auth::user();
    //     $id= $user->PermitNumber;

    //         $data = request()->validate([
    //             'job_id'=>'numeric|Nullable',
    //             'date'=>'Date|required',
    //             'vehicle'=>'numeric',
    //             'customer_name'=>'required',
    //             'fare'=>'numeric|required',
    //             'tip'=>'required|numeric',
    //             'account'=>'required',
    //             'po'=>'required',
    //             'pickup'=>'required',
    //             'dropoff'=>'required',
    //             'pickup_date'=>'Date',
    //             'dropoff__date'=>'Date',
    //         ]);

    //         $data['total']=$data['tip']+$data['fare'];
    //         $data['badge_id']=$id;

    //         Trip::
    //         where('trip_id', $trip_id)
    //         ->where('badge_id','=',$id )
    //         ->update($data);
    //         $trip =  Trip::where('trip_id', $trip_id)
    //         ->first();
    //         return new TripResource($trip);

    // }

    // public function destroy($trip_id)
    // {
    //     $user = Auth::user();
    //     $id= $user->PermitNumber;

    //     $trip =  Trip::
    //     where('trip_id', $trip_id)
    //     ->where('badge_id','=',$id )
    //     ->first();
    //     if($trip->delete())
    //     {
    //         return new TripResource($trip);
    //     }

    // }

}
