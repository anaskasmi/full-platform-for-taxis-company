<?php

namespace App\Http\Controllers\Admin;

use App\AccountSlip;
use App\Batch;
use App\Exports\AccountSlipExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountSlip as AccountSlipResource;
use App\Trip;
use Barryvdh\DomPDF\Facade as PDF;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class AccountSlipsController extends Controller
{
    public function generatePDFs($batch_id)
    {

        // We fetch all the trips between two dates : $start_date and $end_date
        $AccountSlips = AccountSlip::where('batch_id', '=', $batch_id)->get();

        // we loop on the fetched trips
        foreach ($AccountSlips as $AccountSlip) {

            $pdf = PDF::loadView('invoice', array('row' => $AccountSlip));

            // We got the content of the PDF File and store it in $content variable

            $content = $pdf->download()->getOriginalContent();

            // Generating the name of the invoice = invoice_trio_IdOTheTripInvoice.pdf

            $nameOfInvoice = "public/account_slip_" . $AccountSlip->job_id . ".pdf";

            // Finally we store the file :D

            Storage::put($nameOfInvoice, $content);
        }

        // now it's time to zip the files, so we create a ZipArchive object
        $zip = new ZipArchive;

        // We choose the name of the zip
        $fileName = 'accountslips_' . $batch_id . '.zip';

        // we will loop on the files stored in the public/storage folder

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === true) {

            // We get all the files stored in public/storage folder

            $files = File::files(public_path('storage'));

            foreach ($files as $file) {

                // we get the name of the stored file

                $relativeNameInZipFile = basename($file);

                // We add every file to the zip file

                $zip->addFile($file, $relativeNameInZipFile);

            }

            // We close the zip file

            $zip->close();

        }

        // And Finally we download the zip file :D
        return response()->download(public_path($fileName));

    }

    public function download($batch_id)
    {

        return Excel::download(new AccountSlipExport($batch_id), 'MDT.xlsx');

    }

    public function loadTrips($batch_id)
    {
        $batch = Batch::where("id", "=", $batch_id)->get();
        if ($batch->number == 1) {
            $startDay = 01;
            $endDay = 10;
        } else if ($batch->number == 2) {
            $startDay = 11;
            $endDay = 20;
        } else if ($batch->number == 3) {
            $startDay = 21;
            $endDay = 31;
        }
        $startBatchDate = $startDay . "-" . $batch->month . "-" . $batch->year;
        $endBatchDate = $endDay . "-" . $batch->month . "-" . $batch->year;
        Tirp::where("date", ">", $startBatchDate)
            ->where("date", "<", $endBatchDate)
            ->where("account", "not like", "")
            ->get();

    }

    public function all($batch_id, $envelope_id)
    {

        $AccountSlips = AccountSlip::
            where('batch_id', 'LIKE', $batch_id)
            ->where(function ($query) use ($envelope_id) {
                $query->whereNull('envelope_id')
                    ->orWhere('envelope_id', 'LIKE', $envelope_id);
            })
            ->where('status', 'LIKE', "verified")
            ->orderBy('envelope_id', 'desc')
            ->orderBy('slip_id', 'desc')
            ->orderBy('status', 'desc')
            ->get();
        foreach ($AccountSlips as $AccountSlip) {
            $AccountSlip->time = date("H:i", strtotime($AccountSlip->date));
            $AccountSlip->date = date("Y-m-d", strtotime($AccountSlip->date));

        }
        return AccountSlipResource::collection($AccountSlips);

    }

    public function search(Request $request)
    {
        $data = request()->validate([
            'job_id' => '',
            'batch_id' => '',
            'envelope_id' => '',
        ]);

        $AccountSlips = AccountSlip::
            where('job_id', 'LIKE', '%' . $data['job_id'] . '%')
            ->where('batch_id', 'LIKE', $data['batch_id'])
            ->where(function ($query) use ($data) {
                $query->whereNull('envelope_id')
                    ->orWhere('envelope_id', 'LIKE', $data['envelope_id']);
            })
            ->get();
        foreach ($AccountSlips as $AccountSlip) {
            $AccountSlip->time = date("H:i", strtotime($AccountSlip->date));
            $AccountSlip->date = date("Y-m-d", strtotime($AccountSlip->date));

        }
        return AccountSlipResource::collection($AccountSlips);
    }

    public function store(Request $request)
    {

        $data = request()->validate([
            'envelope_id' => 'required',
            'amount' => 'required',
            'batch_id' => 'required',
            'slip_id' => 'required|unique:accountSlips,slip_id',
            'date' => '',
        ]);
        $data['date'] = date("Y-m-d", strtotime($data['date'])) . ' ' . $request->input('time') . ':00';
        $AccountSlip = AccountSlip::create($data);
        return new AccountSlipResource($AccountSlip);

    }

    public function importTrip($job_id, $batch_id)
    {
        if (!is_numeric($job_id)) {
            return response()->json([
                'message' => 'Job ID provided is not a number !'], 400);
        }

        $AccountSlips = AccountSlip::
            where('job_id', 'LIKE', $job_id)
            ->where('batch_id', 'LIKE', $batch_id)
            ->first();

        if ($AccountSlips) {
            return response()->json([
                'message' => 'Sorry, This Account slip already exist !'], 400);

        }
        $trip = Trip::where('job_id', $job_id)->first();

        if ($trip) {

            //new account slip
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
            $accountSlip->fare = (int) ($trip->fare * 100);
            $accountSlip->tip = (int) ($trip->tip * 100);
            $accountSlip->total = (int) ($trip->total * 100);
            $accountSlip->account = $trip->account;
            $accountSlip->PO = $trip->po;
            //add status = unverified
            $accountSlip->status = "unverified";
            //save
            $accountSlip->save();



            //search for the imported account slip, change the date and time and return it
            $accountSlipUpdated = AccountSlip::
                where('job_id', '=', $trip->job_id)
                ->first();
            $accountSlipUpdated->time = date("H:i", strtotime($accountSlipUpdated->date));
            $accountSlipUpdated->date = date("Y-m-d", strtotime($accountSlipUpdated->date));

            return new AccountSlipResource($accountSlipUpdated);
            // return response()->json('Trip was imported successfully !', 200);
        } else {
            return response()->json([
                'message' => 'Trip was not found !'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $data = request()->validate([
            'job_id' => '',
            'envelope_id' => 'required',
            'slip_id' => 'required',
            'status' => '',
            'image_id' => 'Nullable',
            'date' => '',
            'vehicle' => '',
            'badge_id' => '',
            'customer_name' => '',
            'fare' => '',
            'tip' => '',
            'account' => 'required',
            'PO' => '',
            'pickup' => '',
            'dropoff' => '',
        ]);

        $accountSlipToUpdate = AccountSlip::
            where('id', '=', $id)
            ->first();

        if ($accountSlipToUpdate->slip_id != $data['slip_id']) {
            $existedAccountSlip = AccountSlip::
                where('batch_id', '=', $accountSlipToUpdate->batch_id)
                ->where('slip_id', '=', $data['slip_id'])
                ->where('envelope_id', '=', $data['envelope_id'])
                ->first();
            if ($existedAccountSlip) {
                return response()->json([
                    'message' => 'Sorry, This Slip ID already exist !'], 400);
            }
        }
        if ($accountSlipToUpdate->status == "verified" && $data['status'] == "unverified") {
            $data["envelope_id"] = null;
            $data["slip_id"] = null;
            $data["image_id"] = null;
        }
        $data['date'] = date("Y-m-d", strtotime($data['date'])) . ' ' . $request->input('time') . ':00';
        if ($data['envelope_id'] && $data['slip_id']) {
            $data['image_id'] = "M-" . $accountSlipToUpdate->batch_id . '-' . $data['envelope_id'] . '-' . $data['slip_id'];
        }
        $data['total'] = $data['tip'] + $data['fare'];

        try {

            AccountSlip::whereId($id)->update($data);
            $AccountSlip = AccountSlip::whereId($id)->first();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => $e->errorInfo[2],
            ]);
        }
        return new AccountSlipResource($AccountSlip);
    }

    public function show($id)
    {
        $AccountSlip = AccountSlip::where('id', $id)->firstOrFail();
        return new AccountSlipResource($AccountSlip);
    }

    public function destroy($id)
    {
        $AccountSlip = AccountSlip::findOrFail($id);
        if ($AccountSlip->delete()) {
            return new AccountSlipResource($AccountSlip);
        }
    }
    public function fixImageIdOfThisBatch()
    {
        $numberOfFixedSlips = 0;
        $accountSlips = AccountSlip::where('batch_id', 'LIKE', 'B2042020')->get();
        foreach ($accountSlips as $accountSlip) {
            $accountSlip->image_id = "M-" . $accountSlip->batch_id . '-' . $accountSlip->envelope_id . '-' . $accountSlip->slip_id;
            $accountSlip->save();
            $numberOfFixedSlips++;
        }
        dd("number of Fixed slips is : " . $numberOfFixedSlips);

    }

}
