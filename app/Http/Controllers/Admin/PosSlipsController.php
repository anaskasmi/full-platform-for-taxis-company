<?php

namespace App\Http\Controllers\Admin;

use App\Exports\posSlipExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\posSlip as posSlipResource;
use App\posSlip;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class PosSlipsController extends Controller
{

    public function download($batch_id)
    {

        return Excel::download(new posSlipExport($batch_id), 'POS.xlsx');

        // return Excel::store(new posSlipExport, 'invoices.xlsx', 's3');

    }

    public function all($batch_id,$envelope_id)
    {
        $posSlips = posSlip::
            where('batch_id', 'LIKE', $batch_id)
            ->where('envelope_id', 'LIKE', $envelope_id)
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();
        return posSlipResource::collection($posSlips);
    }

    // public function search(Request $request)
    // {
    //     $data = request()->validate([
    //         'searchValue' => 'required',
    //     ]);

    //     $posSlips = posSlip::
    //     where('envelope_id', 'LIKE', '%' . $data['searchValue'] . '%')
    //     ->orWhere('amount', 'LIKE', '%' . $data['searchValue'] . '%')
    //     ->get();
    //     return posSlipResource::collection($posSlips);
    // }

    public function store(Request $request)
    {

        $data = request()->validate([
            'envelope_id' => 'required',
            'amount' => 'required',
            'batch_id' => 'required',
            'slip_id' => 'required',
        ]);
        $posSlip = posSlip::create($data);
        return new posSlipResource($posSlip);

    }

    public function update(Request $request, $id)
    {

        $data = request()->validate([
            'envelope_id' => 'required',
            'amount' => 'required',
            'batch_id' => 'required',
            'slip_id' => 'required',

        ]);
        try {

            posSlip::whereId($id)->update($data);
            $posSlip = posSlip::whereId($id)->first();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => $e->errorInfo[2],
            ]);
        }
        return new posSlipResource($posSlip);
    }

    public function show($id)
    {
        $posSlip = posSlip::where('id', $id)->firstOrFail();
        return new posSlipResource($posSlip);
    }

    public function destroy($id)
    {
        $posSlip = posSlip::findOrFail($id);
        if ($posSlip->delete()) {
            return new posSlipResource($posSlip);
        }
    }
}
