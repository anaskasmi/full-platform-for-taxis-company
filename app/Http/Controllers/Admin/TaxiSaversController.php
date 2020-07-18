<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TaxiSaverExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaxiSaver as TaxiSaverResource;
use App\TaxiSaver;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class TaxiSaversController extends Controller
{

    public function download($batch_id)
    {
        return Excel::download(new TaxiSaverExport($batch_id), 'TAXI.xlsx');
    }

    public function all($batch_id,$envelope_id)
    {
        $TaxiSavers = TaxiSaver::
            where('batch_id', 'LIKE', $batch_id)
            ->where('envelope_id', 'LIKE', $envelope_id)
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();
        return TaxiSaverResource::collection($TaxiSavers);
    }

    // public function search(Request $request)
    // {
    //     $data = request()->validate([
    //         'searchValue' => 'required',
    //     ]);

    //     $TaxiSavers = TaxiSaver::
    //     where('envelope_id', 'LIKE', '%' . $data['searchValue'] . '%')
    //     ->orWhere('amount', 'LIKE', '%' . $data['searchValue'] . '%')
    //     ->get();
    //     return TaxiSaverResource::collection($TaxiSavers);
    // }

    public function store(Request $request)
    {

        $data = request()->validate([
            'envelope_id' => 'required',
            'amount' => 'required',
            'batch_id' => 'required',
            'slip_id' => 'required',
            'date' => 'required|date',

        ]);

        $TaxiSaverExisted = TaxiSaver::
            where('batch_id', '=', $data['batch_id'])
            ->where('slip_id', '=', $data['slip_id'])
            ->where('envelope_id', '=', $data['envelope_id'])
            ->first();
        if ($TaxiSaverExisted) {
            return response()->json([
                'message' => 'Sorry, This Slip ID already exist !'], 400);
        }

        $TaxiSaver = TaxiSaver::create($data);
        return new TaxiSaverResource($TaxiSaver);

    }

    public function update(Request $request, $id)
    {

        $data = request()->validate([
            'envelope_id' => 'required',
            'amount' => 'required',
            'batch_id' => 'required',
            'slip_id' => 'required',
            'date' => 'required|date',
        ]);


        $TaxiSaverToUpdate = TaxiSaver::where('id','=',$id)->first();
        if($TaxiSaverToUpdate->slip_id!=$data['slip_id'])
        {
            $TaxiSaverExisted = TaxiSaver::
            where('batch_id', '=', $data['batch_id'])
            ->where('slip_id', '=', $data['slip_id'])
            ->where('envelope_id', '=', $data['envelope_id'])
            ->first();
            if ($TaxiSaverExisted) {
                return response()->json([
                    'message' => 'Sorry, This Slip ID already exist !'], 400);
            }
        }

        try {

            TaxiSaver::whereId($id)->update($data);
            $TaxiSaver = TaxiSaver::whereId($id)->first();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => $e->errorInfo[2],
            ]);
        }
        return new TaxiSaverResource($TaxiSaver);
    }

    public function show($id)
    {
        $TaxiSaver = TaxiSaver::where('id', $id)->firstOrFail();
        return new TaxiSaverResource($TaxiSaver);
    }

    public function destroy($id)
    {
        $TaxiSaver = TaxiSaver::findOrFail($id);
        if ($TaxiSaver->delete()) {
            return new TaxiSaverResource($TaxiSaver);
        }
    }
}
