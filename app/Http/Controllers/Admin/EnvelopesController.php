<?php

namespace App\Http\Controllers\Admin;

use App\Envelope;
use App\AccountSlip;
use App\TaxiSaver;
use App\posSlip;
use App\Exports\EnvelopeExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Envelope as envelopeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class EnvelopesController extends Controller
{

    public function download($batch_id)
    {
        return Excel::download(new envelopeExport($batch_id), 'ADD-NEW-ENVELOPES.xlsx');
    }

    public function all($batch_id)
    {
        $envelope = Envelope::
            where('batch_id', 'LIKE', $batch_id)
            ->orderBy('envelope_id', 'desc')
            ->get();
        return envelopeResource::collection($envelope);
    }


    // public function search(Request $request)
    // {
    //     $data = request()->validate([
    //         'searchValue' => 'required',
    //     ]);

    //     $envelope = Envelope::
    //     where('envelope_id', 'LIKE', '%' . $data['searchValue'] . '%')
    //     ->orWhere('amount', 'LIKE', '%' . $data['searchValue'] . '%')
    //     ->get();
    //     return envelopeResource::collection($envelope);
    // }
    public function summary($id)
    {
        //get envelope
        $envelope = Envelope::where('id', $id)->firstOrFail();
        $envelope_id = $envelope->envelope_id;
        $batch_id = $envelope->batch_id;
        //get account slips
        $AccountSlips = AccountSlip::
        where('batch_id', 'LIKE', $batch_id)
        ->where(function ($query) use ($envelope_id) {
            $query  ->whereNull('envelope_id')
            ->orWhere('envelope_id', 'LIKE', $envelope_id); 
        })
        ->where('status', 'LIKE', "verified")
        ->get();
        //get taxi savers
        $TaxiSavers = TaxiSaver::
        where('batch_id', 'LIKE', $batch_id)
        ->where('envelope_id', 'LIKE', $envelope_id)
        ->get();
        //get pos slips
        $posSlips = posSlip::
        where('batch_id', 'LIKE', $batch_id)
        ->where('envelope_id', 'LIKE', $envelope_id)
        ->get();
        //add data to summary
        $summary['unverified_envelope_total']=$envelope->unverified_envelope_total;
        $summary['original_total_claimed']=$envelope->original_total_claimed;

        //account slips
        $accountSlipsTotal = 0;
        foreach ($AccountSlips as $AccountSlip) {
            $accountSlipsTotal = $accountSlipsTotal + $AccountSlip->total;
        }
        $summary['mdt_verified']=$accountSlipsTotal/100;
        $summary['mdt_claimed']=$envelope->mdt_claimed;
        $summary['mdt_count']=count($AccountSlips);
        //taxi savers
        $taxiSaversTotal = 0;
        foreach ($TaxiSavers as $TaxiSaver) {
            $taxiSaversTotal = $taxiSaversTotal + $TaxiSaver->amount;
        }
        $summary['taxi_savers_verified']=$taxiSaversTotal;
        $summary['taxi_savers_claimed']=$envelope->taxi_savers_claimed;
        $summary['taxi_savers_count']=count($TaxiSavers);


        //pos Slips
        $posSlipsTotal = 0;
        foreach ($posSlips as $posSlip) {
            $posSlipsTotal = $posSlipsTotal + $posSlip->amount;
        }
        $summary['pos_slips_verified']=$posSlipsTotal;
        $summary['pos_slips_claimed']=$envelope->pos_claimed;
        $summary['pos_slips_count']=count($posSlips);



        return $summary;
    }
    public function store(Request $request)
    {

        $data = request()->validate([
            'batch_id' => 'required',
            'batch_number' => 'required',
            'month' => 'required',
            'year' => 'required',
            'envelope_id' => 'required',
            'operator_id' => 'required',
            'driver_id' => 'required',
            'mdt_claimed' => 'required',
            'taxi_savers_claimed' => 'required',
            'gift_certificates_claimed' => 'required',
            'pos_claimed' => 'required',
            'unverified_envelope_total' => 'required',
            'original_total_claimed' => 'required',
        ]);

        $data["status"]="UNPROCESSED";
        

        $envelope = Envelope::create($data);
        return new envelopeResource($envelope);

    }

    public function update(Request $request, $id)
    {

        $data = request()->validate([
            'batch_id' => 'required',
            'batch_number' => 'required',
            'month' => 'required',
            'year' => 'required',
            'envelope_id' => 'required',
            'operator_id' => 'required',
            'driver_id' => 'required',
            'mdt_claimed' => 'required',
            'taxi_savers_claimed' => 'required',
            'gift_certificates_claimed' => 'required',
            'pos_claimed' => 'required',
            'unverified_envelope_total' => 'required',
            'original_total_claimed' => 'required',
            'status'=>"",

        ]);
        try {

            Envelope::whereId($id)->update($data);
            $envelope = Envelope::whereId($id)->first();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => $e->errorInfo[2],
            ]);
        }
        return new envelopeResource($envelope);
    }

    public function show($id)
    {
        $envelope = Envelope::where('id', $id)->firstOrFail();
        return new envelopeResource($envelope);
    }

    public function destroy($id)
    {
        $envelope = Envelope::findOrFail($id);
        if ($envelope->delete()) {
            return new envelopeResource($envelope);
        }
    }
}
