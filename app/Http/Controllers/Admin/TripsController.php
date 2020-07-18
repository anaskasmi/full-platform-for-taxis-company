<?php

namespace App\Http\Controllers\Admin;

use App\AccountSlip;
use App\Http\Controllers\Controller;
use App\Http\Resources\Trip as TripResource;
use App\Trip;
use DB;
use Illuminate\Http\Request;

class TripsController extends Controller
{

    public function index()
    {
        $trips = Trip::
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
            ->orderByRaw('date DESC')
            ->orderByRaw('trip_id')
            ->paginate(20)
            ->appends(request()->query());
        return TripResource::collection($trips);
    }

    public function search(Request $request)
    {
        //Search by Job id
        //dd($request->query('name'));

        if ($request->input('searchbyjobid')) {
            if (!is_numeric($request->input('searchbyjobid'))) {
                return response()->json([
                    'error' => "Trip Id must be numeric",
                ]);
            }
            $data = request()->validate([
                'searchbyjobid' => 'numeric',
            ]);

            $trips = Trip::
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
                ->where('job_id', '=', $data['searchbyjobid'])
                ->orderByRaw('trip_id DESC')
                ->paginate(20)
                ->appends(request()->query());

            return TripResource::collection($trips);

        }
        //Search by Date
        if ($request->input('searchbydate')) {
            $data = request()->validate([
                'searchbydate' => 'date',
            ]);
            $date = strtotime($data['searchbydate']);
            $date = date('Y-m-d', $date);

            $trips = Trip::
                join('drivers', 'PermitNumber', '=', 'badge_id')
                ->where('date', 'LIKE', '%' . $date . '%')
                ->orderByRaw('date DESC')
                ->orderByRaw('trip_id')
                ->paginate(20)
                ->appends(request()->query());
            return TripResource::collection($trips);

        }
        //Search by Driver
        if ($request->input('searchbydriver')) {
            $data = request()->validate([
                'searchbydriver' => 'required',
            ]);
            $trips = Trip::
                join('drivers', 'PermitNumber', '=', 'badge_id')
                ->where('LastName', 'LIKE', '%' . $data['searchbydriver'] . '%')
                ->orwhere('FirstName', 'LIKE', '%' . $data['searchbydriver'] . '%')
                ->orwhere('PermitNumber', 'LIKE', '%' . $data['searchbydriver'] . '%')
                ->orderByRaw('date DESC')
                ->paginate(20)
                ->appends(request()->query());
            return TripResource::collection($trips);

        }
        //Search by customer
        if ($request->input('searchbycustomer')) {
            $data = request()->validate([
                'searchbycustomer' => 'required',
            ]);
            $trips = Trip::join('drivers', 'PermitNumber', '=', 'badge_id')
                ->where('customer_name', 'LIKE', '%' . $data['searchbycustomer'] . '%')
                ->orderByRaw('date DESC')
                ->paginate(20)
                ->appends(request()->query());
            return TripResource::collection($trips);

        }
    }

    public function show($id)
    {
        //this method make sure that the driver exist
        // $trip = Trip::join('drivers', 'PermitNumber', '=', 'badge_id')
        //     ->where('trip_id', (int)$id)
        //     ->firstOrFail();
        //     return new TripResource($trip);

        //this methode will return the trip even if it didnt find the driver
        $trip = DB::table('piccolo_trips')->Where('trip_id', $id)->get();
        return new TripResource(collect($trip[0]));
    }

    public function store(Request $request)
    {
        $data = request()->validate([
            'job_id' => 'required|numeric',
            'date' => 'Date|required',
            'badge_id' => 'required|numeric',
            'vehicle' => 'required|numeric',
            'customer_name' => 'required',
            'fare' => 'numeric|required',
            'tip' => 'required|numeric',
            'account' => 'required',
            'po' => '',
            'pickup' => 'required',
            'dropoff' => 'required',
            'pickup_date' => 'Nullable|Date',
            'dropoff__date' => 'Nullable|Date',
        ]);
        if($request->input('time'))
        {
            $data['date'] =date("Y-m-d", strtotime( $data['date'])) .' '. $request->input('time').':00';
        }

        if (!array_key_exists('account', $data)) {
            $data['account'] = "";
        }

        if (!array_key_exists('po', $data)) {
            $data['po'] = "";
        }
        $data['total'] = $data['tip'] + $data['fare'];
        $trip = Trip::create($data);

        // if the trip has account or batch_id create an account slip for it
        if ($data['account'] != "" || $request->input('batch_id')) {
            // add batch id
            if ($request->input('batch_id')) {
                $accountSlip['batch_id'] = $request->input('batch_id');
            }
            //add envelope id
            if ($request->input('envelope_id')) {
                $accountSlip['envelope_id'] = $request->input('envelope_id');
            }
            //check if slip_id exist on the same envelope
            if ($request->input('envelope_id') && $request->input('slip_id') && $request->input('batch_id')) 
            {
                $existedAccountSlip = AccountSlip::
                where('batch_id', '=', $request->input('batch_id'))
                ->where('envelope_id', '=', $request->input('envelope_id'))
                ->where('slip_id', '=',$request->input('slip_id'))
                ->first();
                if ($existedAccountSlip) {
                    //if exist return error message
                    return response()->json([
                        'message' => 'Sorry, This Slip ID already exist !'], 400);
                } else {
                    //if it doesnt exist affect slip id and create image id
                    $accountSlip['slip_id'] = $request->input('slip_id');
                    $accountSlip['image_id'] = "M-" . $request->input('batch_id') . '-' . $request->input('envelope_id') . '-' . $request->input('slip_id');
                }
                

            } else {

                //batch Id creation
                $year = date("Y", strtotime($data['date']));
                $month = date("m", strtotime($data['date']));
                $day = date("d", strtotime($data['date']));
                if ((int) $day <= 10 && (int) $day >= 1) {
                    $accountSlip['batch_id'] = 'B1' . $month . $year;
                } else if ((int) $day <= 20 && (int) $day >= 11) {
                    $accountSlip['batch_id'] = 'B2' . $month . $year;
                } else if ((int) $day >= 21) {
                    $accountSlip['batch_id'] = 'B3' . $month . $year;
                }
            }

            //status
            if ($request->input('status')) {
                $accountSlip['status'] = $request->input('status');
            } else {
                $accountSlip['status'] = "unverified";
            }

            $accountSlip['job_id'] = $data['job_id'];
            if($request->input('time'))
            {
                $accountSlip['date'] =date("Y-m-d", strtotime( $data['date'])) .' '. $request->input('time').':00';
            }
            $accountSlip['vehicle'] = $data['vehicle'];
            $accountSlip['badge_id'] = $data['badge_id'];
            $accountSlip['customer_name'] = $data['customer_name'];
            $accountSlip['pickup'] = $data['pickup'];
            $accountSlip['dropoff'] = $data['dropoff'];
            $accountSlip['fare'] = $data['fare'];
            $accountSlip['tip'] = $data['tip'];
            $accountSlip['total'] = $data['total'];
            $accountSlip['account'] = $data['account'];
            $accountSlip['PO'] = $data['po'];
            AccountSlip::create($accountSlip);

        }

        return new TripResource($trip);
    }

    public function update(Request $request, $id)
    {
        $data = request()->validate([
            'job_id' => 'numeric|Nullable',
            'date' => 'Date|required',
            'badge_id' => 'numeric',
            'vehicle' => 'numeric',
            'customer_name' => 'required',
            'fare' => 'numeric|required',
            'tip' => 'required|numeric',
            'account' => 'Nullable',
            'po' => 'Nullable',
            'pickup' => 'required',
            'dropoff' => 'required',
            'pickup_date' => 'Date',
            'dropoff__date' => 'Date',
        ]);
        if ($data['account'] === null) {
            $data['account'] = "";
        }
        if ($data['po'] === null) {
            $data['po'] = "";
        }
        $data['total'] = $data['tip'] + $data['fare'];
        Trip::where('trip_id', $id)->update($data);
        //update account slip
        if ($accountSlipToUpdate = AccountSlip::where('job_id', 'LIKE', $data['job_id'])->first()) {

            if ($accountSlipToUpdate->date != $data['date']) {
                $accountSlip['date'] = $data['date'];
                $year = date("Y", strtotime($data['date']));
                $month = date("m", strtotime($data['date']));
                $day = date("d", strtotime($data['date']));
                if ((int) $day <= 10 && (int) $day >= 1) {
                    $accountSlip['batch_id'] = 'B1' . $month . $year;
                } else if ((int) $day <= 20 && (int) $day >= 11) {
                    $accountSlip['batch_id'] = 'B2' . $month . $year;
                } else if ((int) $day >= 21) {
                    $accountSlip['batch_id'] = 'B3' . $month . $year;
                }

            }
            $accountSlip['vehicle'] = $data['vehicle'];
            $accountSlip['badge_id'] = $data['badge_id'];
            $accountSlip['customer_name'] = $data['customer_name'];
            $accountSlip['pickup'] = $data['pickup'];
            $accountSlip['dropoff'] = $data['dropoff'];
            $accountSlip['fare'] = $data['fare'];
            $accountSlip['tip'] = $data['tip'];
            $accountSlip['total'] = $data['total'];
            $accountSlip['account'] = $data['account'];
            $accountSlip['PO'] = $data['po'];
            AccountSlip::where('job_id', 'LIKE', $data['job_id'])->update($accountSlip);

        }

        $trip = Trip::where('trip_id', $id)->first();
        return new TripResource($trip);

    }

    public function destroy($id)
    {

        $trip = Trip::where('trip_id', $id)->first();
        $job_id = $trip->job_id;
        if ($trip->delete()) {
            $accountSlip = AccountSlip::where('job_id', 'LIKE', $job_id)->first();
            if ($accountSlip->delete()) {
                return new TripResource($trip);
            }

        }

    }

}
