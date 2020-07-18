<?php

namespace App\Http\Controllers\Admin;

use App\AccountSlip;
use App\Batch;
use App\Http\Controllers\Controller;
use App\Http\Resources\Batch as BatchResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Trip;

class BatchesController extends Controller
{

    public function all($year)
    {
        $batches = Batch::
            where('year', '=', $year)
            ->orderBy('created_at', 'desc')
            ->get();
        return BatchResource::collection($batches);
    }

    // public function search(Request $request)
    // {
    //     $data = request()->validate([
    //         'searchValue' => 'required',
    //                 ]);

    //     $batches = Batch::where('name','LIKE','%'.$data['searchValue'].'%')
    //     ->paginate(30)
    //     ->appends(request()->query());
    //     return BatchResource::collection($batches);
    // }

    public function store(Request $request)
    {

        $batchExist = Batch::where('batch_id', '=', request('batch_id'))->first();
        if ($batchExist) {
            return response()->json('this batch is already Created!.', 400);
        } else {
            $data = request()->validate([
                'batch_id' => 'unique:batches,batch_id',
                'number' => 'required',
                'month' => 'required',
                'year' => 'required',
                'status' => 'required',
            ]);

            $batch = Batch::create($data);
            try {
                $this->deleteTripsOfBatch($batch->batch_id);
                $this->getTripsToBatch(request('batch_id'));

            } catch (\Throwable $th) {
                return 'error in getting trips ';
            }

            return new BatchResource($batch);
        }

    }

    public function getTripsToBatch($batch_id)
    {
        //get batch
        $batch = Batch::where('batch_id', '=', $batch_id)->first();
        //store date start and date end of batch
        $start_date = $batch->year . "-" . $batch->month . "-";
        $end_date = $batch->year . "-" . $batch->month . "-";

        if ($batch->number == 1) {
            $start_date = $start_date . "01";
            $end_date = $end_date . "12";

        } else if ($batch->number == 2) {
            $start_date = $start_date . "9";
            $end_date = $end_date . "22";

        } else if ($batch->number == 3) {
            $start_date = $start_date . "19";
            $end_date = $end_date . "33";

        }

        $trips = Trip::
        where('dropoff__date', '>=', $start_date)->where('date', '<=',  $end_date)
        ->where('account', '<>', '')
        ->whereNotNull('account')
        ->get();
        //loop trough the trips
        foreach ($trips as $trip) {
            //create account slip
            $accountSlip = new AccountSlip;
            //add batch id
            $accountSlip->batch_id = $batch_id;
            //add job id
            $accountSlip->job_id = $trip->job_id;
            $accountSlip->date = $trip->date;
            $accountSlip->vehicle = $trip->vehicle;
            $accountSlip->badge_id = $trip->badge_id;
            $accountSlip->customer_name = $trip->customer_name;
            $accountSlip->pickup = $trip->pickup;
            $accountSlip->dropoff = $trip->dropoff;
            $accountSlip->fare = (int)($trip->fare*100);
            $accountSlip->tip = (int)($trip->tip*100);
            $accountSlip->total =(int)($trip->total*100);
            $accountSlip->account = $trip->account;
            $accountSlip->PO = $trip->po;
            //add status = unverified
            $accountSlip->status = "unverified";
            //save
            $checkAccountSlip = AccountSlip::
            where('job_id', $trip->job_id)
            ->where('batch_id', $batch->batch_id)

            ->first();

            if (!$checkAccountSlip) {

                    $accountSlip->save();
            
            }

        }

    }

    public function getTripsToCurrentBatch($id)
    {
        $numberOfAddedTrips= 0;
        //get batch
        $batch = Batch::where('id', '=', $id)->first();

        // if (!$batch) {

        //     return response()->json([
        //         'message' => 'Sorry, no batch is under processing !'], 400);

        // }
        //store date start and date end of batch
        $start_date = $batch->year . "-" . $batch->month . "-";
        $end_date = $batch->year . "-" . $batch->month . "-";

        if ($batch->number == 1) {
            $start_date = $start_date . "01";
            $end_date = $end_date . "12";

        } else if ($batch->number == 2) {
            $start_date = $start_date . "9";
            $end_date = $end_date . "22";

        } else if ($batch->number == 3) {
            $start_date = $start_date . "19";
            $end_date = $end_date . "33";

        }

        $trips = Trip::
        where('dropoff__date', '>=', $start_date)->where('date', '<=',  $end_date)
        ->where('account', '<>', '')
        ->whereNotNull('account')
        ->get();
       

        //loop trough the trips
        foreach ($trips as $trip) {

            //save
            $checkAccountSlip = AccountSlip::
            where('job_id', $trip->job_id)
            ->where('batch_id', $batch->batch_id)
            ->first();
            if (!$checkAccountSlip) {
                //create account slip
                $accountSlip = new AccountSlip;
                //add batch id
                $accountSlip->batch_id = $batch->batch_id;
                //add job id
                $accountSlip->job_id = $trip->job_id;
                $accountSlip->date = $trip->dropoff__date;
                $accountSlip->vehicle = $trip->vehicle;
                $accountSlip->badge_id = $trip->badge_id;
                $accountSlip->customer_name = $trip->customer_name;
                $accountSlip->pickup = $trip->pickup;
                $accountSlip->dropoff = $trip->dropoff;
                $accountSlip->fare = (int)($trip->fare*100);
                $accountSlip->tip = (int)($trip->tip*100);
                $accountSlip->total =(int)($trip->total*100);
                $accountSlip->account = $trip->account;
                $accountSlip->PO = $trip->po;
                //add status = unverified
                $accountSlip->status = "unverified";
                $accountSlip->save();
                $numberOfAddedTrips ++;
            }

            //envelope_id will be filled later
            //image_id = M-B1082019-42-5.pdf will be filled later
        }
        return $numberOfAddedTrips;

    }

    public function deleteTripsOfBatch($batch_id)
    {
        $AccountSlips = AccountSlip::
            where('batch_id', 'LIKE', $batch_id)
            ->delete();
    }

    public function update(Request $request, $id)
    {
        $batchToUpdate = Batch::where('id', '=', $id)->first();

        //if we want to chane the batch ID
        if ($batchToUpdate->batch_id != $request->input('batch_id')) {

            $isBatchExist = Batch::where('batch_id', '=', $request->input('batch_id'))->first();
            if ($isBatchExist) {
                return response()->json('this batch is already Exist!.', 400);
            } else {

                $data = request()->validate([
                    'batch_id' => 'required',
                    'number' => 'required',
                    'month' => 'required',
                    'year' => 'required',
                    'status' => 'required',
                ]);
                if ($data['status'] == 'UNDER-PROCESSING') {

                    $openedBatch = Batch::where('status', '=', 'UNDER-PROCESSING')->first();
                    if ($openedBatch) {
                        return response()->json(['message' => 'Sorry, Another batch is under processing !'], 400);
                    }

                }
                try {
                    Batch::whereId($id)->update($data);
                    $batch = Batch::whereId($id)->first();
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'error' => $e->errorInfo[2],
                    ]);
                }

                return new BatchResource($batch);
            }
        }
        //  if the changement doesnt include the batch ID
        else {

            $data = request()->validate([
                'batch_id' => 'required',
                'number' => 'required',
                'month' => 'required',
                'year' => 'required',
                'status' => 'required',
            ]);
            if ($data['status'] == 'UNDER-PROCESSING') {
                $openedBatch = Batch::where('status', '=', 'UNDER-PROCESSING')->first();
                if ($openedBatch) {

                    return response()->json(['message' => 'Sorry, Another batch is under processing !'], 400);
                }
            }
            try {
                Batch::whereId($id)->update($data);
                $batch = Batch::whereId($id)->first();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'error' => $e->errorInfo[2],
                ]);
            }

            return new BatchResource($batch);
        }
    }

    public function show($id)
    {
        $batch = Batch::where('id', $id)->firstOrFail();
        return new BatchResource($batch);
    }

    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);
        $this->deleteTripsOfBatch($batch->batch_id);
        //delete envelopes
        //delepe pos slips
        //delete taxi savers
        if ($batch->delete()) {
            return new BatchResource($batch);
        }
    }
}
